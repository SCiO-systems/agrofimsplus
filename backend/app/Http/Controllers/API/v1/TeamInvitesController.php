<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeamInvites\CreateTeamInviteRequest;
use App\Http\Resources\v1\InviteResource;
use App\Models\Invite;
use App\Models\Team;
use App\Models\User;

class TeamInvitesController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTeamInviteRequest $request, Team $team)
    {
        // Add the user id to a collection.
        $users = collect($request->users);

        // The user attempted to invite themselves.
        if ($users->contains($request->user()->id)) {
            return response()->json([
                'errors' => [
                    'error' => 'You cannot invite yourself to your own team.'
                ]
            ], 409);
        }

        // Check for already invited users.
        $invited = Invite::whereIn('id', $users)
            ->where('team_id', $team->id)
            ->pluck('id');

        // Invite the non-invited emails.
        $ids = $users->diff($invited)->each(function ($id) use ($team) {
            Invite::create(['user_id' => $id, 'team_id' => $team->id]);
        });

        // TODO: Send the invites. Gather the sent invites.
        $invites = Invite::whereIn('id', $ids)->get();

        return InviteResource::collection($invites);
    }
}
