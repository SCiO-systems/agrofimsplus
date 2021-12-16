<?php

namespace App\Services\FairScoring\Rules\Findable;

use App\Services\FairScoring\Interfaces\FairScoreRule;
use App\Services\FairScoring\Rules\BaseRule;

class ResourceHasDOI extends BaseRule implements FairScoreRule
{
    public static $metadataCondition = '"resource has DOI"';
    public static $scoring = '1 point';
    public static $recommendation = 'Get a DOI for the resource';

    public static function calculateScore($metadataRecord)
    {
        return self::meetsCondition($metadataRecord) ? 1 : 0;
    }

    public static function meetsCondition($metadataRecord)
    {
        if (!empty($identifiers = data_get($metadataRecord, 'identifier'))) {
            foreach ($identifiers as $identifier) {
                if ($identifier['type'] === 'DOI' && !empty($identifier['value'])) {
                    return true;
                }
            }
        }
        return false;
    }
}
