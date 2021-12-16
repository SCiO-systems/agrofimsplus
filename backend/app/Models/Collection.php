<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Collection
 *
 * @property int $id
 * @property int $team_id
 * @property string $title
 * @property string|null $description
 * @property int $inherit_information_to_resources
 * @property int $keywords_extracted_from_resources
 * @property int $publish_as_catalogue_of_resources
 * @property string|null $doi
 * @property string|null $publisher
 * @property string|null $embargo_date
 * @property int $geospatial_coverage_calculated_from_resources
 * @property int $temporal_coverage_calculated_from_resources
 * @property float $findable_score
 * @property float $accessible_score
 * @property float $interoperable_score
 * @property float $reusable_score
 * @property float $fair_scoring
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Resource[] $resources
 * @property-read int|null $resources_count
 * @property-read \App\Models\Team $team
 * @method static \Illuminate\Database\Eloquent\Builder|Collection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection query()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereAccessibleScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereDoi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereEmbargoDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereFairScoring($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereFindableScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereGeospatialCoverageCalculatedFromResources($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereInheritInformationToResources($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereInteroperableScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereKeywordsExtractedFromResources($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection wherePublishAsCatalogueOfResources($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection wherePublisher($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereReusableScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereTemporalCoverageCalculatedFromResources($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Collection extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'collections';

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function resources()
    {
        return $this->belongsToMany(
            Resource::class,
            'collection_resource',
            'collection_id',
            'resource_id'
        );
    }
}
