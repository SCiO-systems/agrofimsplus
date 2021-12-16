<?php

namespace App\Services\FairScoring\Rules\Findable;

use App\Services\FairScoring\Interfaces\FairScoreRule;
use App\Services\FairScoring\Rules\BaseRule;

class SpatialCoverageDefined extends BaseRule implements FairScoreRule
{
    public static $metadataCondition = 'SPATIAL COVERAGE is defined using UN-M49 or ISO3166.1.(a2/a3) terms';
    public static $scoring = '1 point';
    public static $recommendation = 'Define the spatial coverage of the resource';
    public static $anchor = 'resource-spatial-coverage';

    public static function calculateScore($metadataRecord)
    {
        return self::meetsCondition($metadataRecord) ? 1 : 0;
    }

    public static function meetsCondition($metadataRecord)
    {
        if (!empty(data_get($metadataRecord, 'geography.regions')) || !empty(data_get($metadataRecord, 'geography.countries'))) {
            return true;
        }
        return false;
    }
}
