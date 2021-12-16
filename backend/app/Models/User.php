<?php

namespace App\Models;

use App\Enums\ResourceStatus;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $guarded = [];
    protected $hidden = ['password', 'remember_token'];

    public function isPartOfAuthoringTeam($resourceId)
    {
        $entry = DB::table('resource_authors')
            ->where('resource_id', $resourceId)
            ->where('user_id', $this->id)
            ->first();

        return !empty($entry);
    }

    public function isPartOfReviewTeam($resourceId)
    {
        $entry = DB::table('resource_reviewers')
            ->where('resource_id', $resourceId)
            ->where('user_id', $this->id)
            ->first();

        return !empty($entry);
    }

    public function sharedTeams()
    {
        return $this->belongsToMany(Team::class, 'team_user', 'user_id', 'team_id');
    }

    public function ownedTeams()
    {
        return $this->hasMany(Team::class, 'owner_id');
    }

    public function repositories()
    {
        return $this->hasMany(UserRepository::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getAuthoredResourcesCount($statuses = [])
    {
        if (empty($statuses)) {
            $statuses = ResourceStatus::getValues();
        }

        $resources = DB::table('resource_authors')
            ->where('user_id', $this->id)
            ->pluck('resource_id');

        return Resource::whereIn('status', $statuses)
            ->whereIn('id', $resources)
            ->count();
    }

    public function getReviewedResourcesCount($statuses = [])
    {
        if (empty($statuses)) {
            $statuses = ResourceStatus::getValues();
        }

        $resources = DB::table('resource_reviewers')
            ->where('user_id', $this->id)
            ->pluck('resource_id');

        return Resource::whereIn('status', $statuses)
            ->whereIn('id', $resources)
            ->count();
    }
}
