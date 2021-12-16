<?php

namespace App\Models;

use App\Mail\TeamInviteSent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mail;

/**
 * App\Models\Invitation
 *
 * @property int $id
 * @property int $from_user_id
 * @property int $team_id
 * @property string $email
 * @property string $invitation_code
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $inviter
 * @property-read \App\Models\Team $team
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereFromUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereInvitationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Invite extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'invites';

    public static function boot()
    {
        parent::boot();

        static::created(function ($invite) {
            // Mail::to($invite->email)->queue(new TeamInviteSent($invite));
        });
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
