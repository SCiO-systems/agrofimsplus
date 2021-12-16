<?php

namespace App\Http\Controllers\API\v1;

use App\Enums\InviteStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserInvites\UserInviteAcceptRequest;
use App\Http\Requests\UserInvites\UserInviteListRequest;
use App\Http\Requests\UserInvites\UserInviteRejectRequest;
use App\Http\Resources\v1\InviteResource;
use App\Models\Invite;
use App\Models\Team;
use App\Models\User;

class UserInvitesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserInviteListRequest $request, User $user)
    {
        $pending = Invite::where('user_id', $request->user()->id)
            ->where('status', InviteStatus::Pending)
            ->get();

        return InviteResource::collection($pending);
    }

    /**
     * Accept an invite.
     *
     * @return \Illuminate\Http\Response
     */
    public function accept(UserInviteAcceptRequest $request, User $user, Invite $invite)
    {
        $found = $invite->where('status', InviteStatus::Pending)->first();

        if (!$found) {
            return response()->json(['errors' => [
                'error' => 'The invite was not found.'
            ]], 404);
        }

        // Check if the team exists.
        $team = Team::find($invite->team_id);

        if (empty($team)) {
            return response()->json(['errors' => [
                'error' => 'The team for this invite was not found.'
            ]], 404);
        }

        // Check if the user is already part of the team.
        $isMemberAlready = $team->users()->where('team_id', $team->id)
            ->where('user_id', $user->id)
            ->first();

        if (!empty($isMemberAlready)) {
            return response()->json(['errors' => [
                'error' => 'The user is already a part of this team.'
            ]], 409);
        }

        // Check if the user is a team leader.
        $isTeamLeader = $team->owner_id === $user->id;

        if ($isTeamLeader) {
            return response()->json(['errors' => [
                'error' => 'The user is already the leader of this team.'
            ]], 409);
        }

        // Add the user to the team.
        $team->users()->attach($user);

        // Set the invite as accepted.
        $invite->update(['status' => InviteStatus::Accepted]);

        return new InviteResource($invite);
    }


    /**
     * Reject an invite.
     *
     * @return \Illuminate\Http\Response
     */
    public function reject(UserInviteRejectRequest $request, User $user, Invite $invite)
    {
        $found = Invite::where('id', $invite->id)->where('status', InviteStatus::Pending)->first();

        if (!$found) {
            return response()->json(['errors' => ['error' => 'The invite was not found.']], 404);
        }

        $invite->delete();

        return new InviteResource($invite);
    }
}
