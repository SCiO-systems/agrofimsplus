<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CollectionTemporalCoverage
 *
 * @property int $id
 * @property int $collection_id
 * @property string $type
 * @property string|null $description
 * @property string|null $from_date
 * @property string|null $to_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Collection $collection
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionTemporalCoverage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionTemporalCoverage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionTemporalCoverage query()
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionTemporalCoverage whereCollectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionTemporalCoverage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionTemporalCoverage whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionTemporalCoverage whereFromDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionTemporalCoverage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionTemporalCoverage whereToDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionTemporalCoverage whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionTemporalCoverage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CollectionTemporalCoverage extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'collection_temporal_coverages';

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }
}
