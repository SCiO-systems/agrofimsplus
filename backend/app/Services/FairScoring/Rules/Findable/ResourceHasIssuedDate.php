<?php

namespace App\Services\FairScoring\Rules\Findable;

use App\Services\FairScoring\Interfaces\FairScoreRule;
use App\Services\FairScoring\Rules\BaseRule;

class ResourceHasIssuedDate extends BaseRule implements FairScoreRule
{
    public static $metadataCondition = 'RESOURCE has ISSUED DATE defined in metadata';
    public static $scoring = '0.25 points';
    public static $recommendation = 'Provide the Issued Date of the resource';
    public static $anchor = 'resource-issued-date';

    public static function calculateScore($metadataRecord)
    {
        return self::meetsCondition($metadataRecord) ? 0.25 : 0;
    }

    public static function meetsCondition($metadataRecord)
    {
        if (!empty(data_get($metadataRecord, 'release_date'))) {
            return true;
        }
        return false;
    }
}
