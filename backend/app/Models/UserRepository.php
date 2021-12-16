<?php

namespace App\Models;

use App\Enums\RepositoryType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TargetedRepository
 *
 * @property int $id
 * @property int $user_id
 * @property int $type_id
 * @property string $name
 * @property string $api_endpoint
 * @property string|null $client_secret
 * @property int $connection_verified
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|TargetedRepository newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TargetedRepository newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TargetedRepository query()
 * @method static \Illuminate\Database\Eloquent\Builder|TargetedRepository whereApiEndpoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TargetedRepository whereClientSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TargetedRepository whereConnectionVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TargetedRepository whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TargetedRepository whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TargetedRepository whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TargetedRepository whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TargetedRepository whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TargetedRepository whereUserId($value)
 * @mixin \Eloquent
 */
class UserRepository extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'user_repositories';

    public static function boot()
    {
        parent::boot();

        static::created(function ($repository) {
            // TODO: Verify the repository connection.
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function types()
    {
        return RepositoryType::getValues();
    }
}
