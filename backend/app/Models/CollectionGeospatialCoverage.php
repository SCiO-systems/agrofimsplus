<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CollectionGeospatialCoverage
 *
 * @property int $id
 * @property int $collection_id
 * @property string $country
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Collection $collection
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionGeospatialCoverage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionGeospatialCoverage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionGeospatialCoverage query()
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionGeospatialCoverage whereCollectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionGeospatialCoverage whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionGeospatialCoverage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionGeospatialCoverage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionGeospatialCoverage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CollectionGeospatialCoverage extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'collection_geospatial_coverages';

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }
}
