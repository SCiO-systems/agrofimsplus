<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Requests\UserTeams\CreateUserOwnedTeamRequest;
use App\Http\Requests\UserTeams\ListUserOwnedTeamsRequest;
use App\Http\Requests\UserTeams\ShowUserOwnedTeamRequest;
use App\Http\Requests\UserTeams\UpdateUserOwnedTeamRequest;
use App\Http\Resources\v1\UserTeamResource;
use App\Models\Team;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserTeams\ListAllUserOwnedTeamsRequest;
use App\Http\Resources\v1\UserTeamIdNameResource;

class UserTeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ListUserOwnedTeamsRequest $request, User $user)
    {
        $teams = Team::where('owner_id', $user->id)->paginate();

        return UserTeamResource::collection($teams);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserOwnedTeamRequest $request, User $user)
    {
        $team = Team::create([
            'owner_id' => $user->id,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return new UserTeamResource($team);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowUserOwnedTeamRequest $request, User $user, Team $team)
    {
        return new UserTeamResource($team);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserOwnedTeamRequest $request, User $user, Team $team)
    {
        // Filter null and falsy values.
        // TODO: Check for SQLi.
        $data = collect($request->all())->filter()->all();

        // Update the team details with the new ones.
        $team->update($data);

        return new UserTeamResource($team);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // TODO: Implement this in the future maybe.
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all(ListAllUserOwnedTeamsRequest $request, User $user)
    {
        $all = Team::where('owner_id', $user->id)->get();

        return UserTeamIdNameResource::collection($all);
    }
}
