<?php

namespace App\Http\Controllers\API\v1;

use App\Enums\ResourceStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\TeamResourceComments\UpdateTeamResourceCommentsRequest;
use App\Http\Resources\v1\SingleResourceResource;
use App\Models\Resource;
use App\Models\Team;

class TeamResourcesCommentsController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(
        UpdateTeamResourceCommentsRequest $request,
        Team $team,
        Resource $resource
    ) {
        if ($resource->status !== ResourceStatus::UNDER_REVIEW) {
            return response()->json(['errors' => [
                'error' => 'The resource status is not "' . ResourceStatus::UNDER_REVIEW . '".'
            ]], 422);
        }

        $resource->comments = $request->comments;
        $resource->save();

        return new SingleResourceResource($resource);
    }
}
