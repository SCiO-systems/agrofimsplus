<?php

namespace App\Services\FairScoring\Rules\Findable;

use App\Services\FairScoring\Interfaces\FairScoreRule;
use App\Services\FairScoring\Rules\BaseRule;

class AtLeastOneHandlerIsDOI extends BaseRule implements FairScoreRule
{
    public static $metadataCondition = 'At least one HANDLE is a DOI';
    public static $scoring = '0.5 points additional to B';
    public static $recommendation = 'Get a DOI for the resource';
    public static $anchor = 'resource-dois';

    public static function calculateScore($metadataRecord)
    {
        return self::meetsCondition($metadataRecord) ? 1.5 : 0;
    }

    public static function meetsCondition($metadataRecord)
    {
        if (!empty(data_get($metadataRecord, 'dois'))) {
            return true;
        }
        return false;
    }
}
