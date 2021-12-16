<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeamCollections\CreateTeamCollectionRequest;
use App\Http\Requests\TeamCollections\DeleteTeamCollectionRequest;
use App\Http\Requests\TeamCollections\ListSingleTeamCollectionRequest;
use App\Http\Requests\TeamCollections\ListTeamCollectionsRequest;
use App\Http\Requests\TeamCollections\UpdateTeamCollectionRequest;
use App\Http\Resources\v1\TeamCollectionResource;
use App\Models\Collection;
use App\Models\Team;

class TeamCollectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ListTeamCollectionsRequest $request, Team $team)
    {
        $collections = Collection::where('team_id', $team->id)->paginate();

        return TeamCollectionResource::collection($collections);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTeamCollectionRequest $request, Team $team)
    {
        $data = collect($request->all())->filter()->all();
        $data['team_id'] = $team->id;
        $collection = Collection::create($data);

        return new TeamCollectionResource($collection);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(
        ListSingleTeamCollectionRequest $request,
        Team $team,
        Collection $collection
    ) {
        return new TeamCollectionResource($collection);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTeamCollectionRequest $request, Team $team, Collection $collection)
    {
        // Filter null and falsy values.
        // TODO: Check for SQLi.
        $data = collect($request->only(['title', 'description']))->filter()->all();

        // Update the team details with the new ones.
        $collection->update($data);

        return new TeamCollectionResource($collection);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(
        DeleteTeamCollectionRequest $request,
        Team $team,
        Collection $collection
    ) {
        if ($collection->delete()) {
            return response()->json([], 204);
        }

        return response()->json(['errors' => [
            'error' => 'The collection could not be deleted!'
        ]], 400);
    }

    /**
     * Display a listing of all the resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function all(ListTeamCollectionsRequest $request, Team $team)
    {
        $collections = Collection::where('team_id', $team->id)->get();

        return TeamCollectionResource::collection($collections);
    }
}
