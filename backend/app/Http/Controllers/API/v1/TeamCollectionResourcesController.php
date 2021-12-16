<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TeamCollectionResources\ListSingleTeamCollectionResourceRequest;
use App\Http\Requests\TeamCollectionResources\ListTeamCollectionResourcesRequest;
use App\Http\Resources\v1\SingleResourceResource;
use App\Http\Resources\v1\TeamCollectionResourceResource;
use App\Models\Collection;
use App\Models\Resource;
use App\Models\Team;

class TeamCollectionResourcesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(
        ListTeamCollectionResourcesRequest $request,
        Team $team,
        Collection $collection
    ) {
        $resources = $collection->resources();

        if (!empty($request->status)) {
            $resources = $resources->where('status', $request->status);
        }

        $resources = $resources->paginate();

        return TeamCollectionResourceResource::collection($resources);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(
        ListSingleTeamCollectionResourceRequest $request,
        Team $team,
        Collection $collection,
        Resource $resource
    ) {
        return new SingleResourceResource($resource);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
