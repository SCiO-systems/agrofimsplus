<?php

namespace App\Mail;

use App\Models\Invite;
use App\Models\Team;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeamInviteSent extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    // The inviter.
    protected $user;

    // The team.
    protected $team;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invite $invite)
    {
        $this->team = Team::find($invite->team_id);
        $this->user = User::find($this->team->owner_id);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.teams.invites.sent')->with([
            'user' => $this->user,
            'team' => $this->team
        ]);
    }
}
