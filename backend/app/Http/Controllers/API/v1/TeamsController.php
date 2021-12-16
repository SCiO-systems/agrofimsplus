<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teams\DeleteTeamRequest;
use App\Http\Requests\Teams\ListAllTeamsRequest;
use App\Http\Requests\Teams\ListTeamsRequest;
use App\Http\Requests\Teams\ShowSingleTeamRequest;
use App\Http\Resources\v1\TeamResource;
use App\Http\Resources\v1\TeamResourceWithUsersAndOwner;
use App\Models\Team;

class TeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ListTeamsRequest $request)
    {
        $sharedTeams = $request->user()->sharedTeams()->paginate();

        return TeamResource::collection($sharedTeams);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowSingleTeamRequest $request, Team $team)
    {
        return new TeamResourceWithUsersAndOwner($team);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all(ListAllTeamsRequest $request)
    {
        $sharedTeams = $request->user()->sharedTeams()->get();

        return TeamResource::collection($sharedTeams);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteTeamRequest $request, Team $team)
    {
        if ($team->delete()) {
            return response()->json([], 204);
        }

        return response()->json(['errors' => [
            'error' => 'The team could not be deleted!'
        ]], 400);
    }
}
