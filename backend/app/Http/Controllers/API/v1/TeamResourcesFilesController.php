<?php

namespace App\Http\Controllers\API\v1;

use Cache;
use Storage;
use App\Models\Team;
use App\Enums\PIIStatus;
use App\Models\Resource;
use Illuminate\Support\Str;
use App\Jobs\CreatePIICheck;
use App\Models\ResourceFile;
use App\Utilities\SCIO\PIIChecker;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\TeamResourceFileResource;
use App\Http\Requests\TeamResourceFiles\AcceptPIITermsRequest;
use App\Http\Requests\TeamResourceFiles\ShowTeamResourceFileRequest;
use App\Http\Requests\TeamResourceFiles\ListTeamResourceFilesRequest;
use App\Http\Requests\TeamResourceFiles\CreateTeamResourceFileRequest;
use App\Http\Requests\TeamResourceFiles\DeleteTeamResourceFileRequest;

class TeamResourcesFilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ListTeamResourceFilesRequest $request, Team $team, Resource $resource)
    {
        $files = $resource->files()->paginate();

        return TeamResourceFileResource::collection($files);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTeamResourceFileRequest $request, Team $team, Resource $resource)
    {
        $resourceId = $resource->id;
        $file = $request->file('file');
        $name = Str::uuid();
        $directory = "resource_files/$resourceId";
        $saved = Storage::putFileAs($directory, $file, $name);

        $resourceFile = null;
        if ($saved) {
            $resourceFile = ResourceFile::create([
                'resource_id' => $resource->id,
                'user_id' => $request->user()->id,
                'filename' => $file->getClientOriginalName(),
                'path' => "$directory/$name",
                'pii_check_status' => PIIStatus::PENDING,
            ]);

            // Dispatch job for PII check.
            CreatePIICheck::dispatch($resourceFile);
        }

        return new TeamResourceFileResource($resourceFile);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(
        ShowTeamResourceFileRequest $request,
        Team $team,
        Resource $resource,
        ResourceFile $file
    ) {
        return new TeamResourceFileResource($file);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(
        DeleteTeamResourceFileRequest $request,
        Team $team,
        Resource $resource,
        ResourceFile $file
    ) {
        $fileDeleted = Storage::delete($file->path);

        if ($fileDeleted) {
            $dbEntryDeleted = $file->delete();
            if ($dbEntryDeleted) {
                return response()->json([], 204);
            }
        }

        return response()->json(['errors' => [
            'error' => 'Something went wrong'
        ]], 400);
    }

    /**
     * Accept PII terms.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function acceptPIITerms(
        AcceptPIITermsRequest $request,
        Team $team,
        Resource $resource,
        ResourceFile $file
    ) {
        $file->acceptTerms();

        return new TeamResourceFileResource($file);
    }

    /**
     * Get PII report for a file.
     */
    public function getPIIReport(
        AcceptPIITermsRequest $request,
        Team $team,
        Resource $resource,
        ResourceFile $file
    ) {
        $cacheKey = $file->pii_check_status_identifier . '_report';

        if (Cache::has($cacheKey)) {
            return response()->json(Cache::get($cacheKey), 200);
        }

        if ($file->exists()) {
            $checker = new PIIChecker();
            $report = $checker->getReport($file->pii_check_status_identifier);
            Cache::put($cacheKey, $report, env('CACHE_TTL_SECONDS'));
            return response()->json($checker->getReport($file->pii_check_status_identifier), 200);
        }

        return response()->json([], 204);
    }
}
