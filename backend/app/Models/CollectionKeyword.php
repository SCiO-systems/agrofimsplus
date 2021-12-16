<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CollectionKeyword
 *
 * @property int $id
 * @property int $collection_id
 * @property string $keyword
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Collection $collection
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionKeyword newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionKeyword newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionKeyword query()
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionKeyword whereCollectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionKeyword whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionKeyword whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionKeyword whereKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionKeyword whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CollectionKeyword extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'collection_keywords';

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }
}
