<?php

namespace App\Services\FairScoring\Rules\Findable;

use App\Services\FairScoring\Interfaces\FairScoreRule;
use App\Services\FairScoring\Rules\BaseRule;

class ResourceHasHDLorURL extends BaseRule implements FairScoreRule
{
    public static $metadataCondition = 'if ( "no DOI exist" ) then "resource has an HDL or URL"';
    public static $scoring = '0.125 points';
    public static $recommendation = 'if no DOI exist, add or update metadata record with HDL or URL after publishing it';

    public static function calculateScore($metadataRecord)
    {
        return self::meetsCondition($metadataRecord) ? 0.125 : 0;
    }

    public static function meetsCondition($metadataRecord)
    {
        if (!empty($identifiers = data_get($metadataRecord, 'identifier'))) {
            foreach ($identifiers as $identifier) {
                if ($identifier['type'] === 'DOI' && !empty($identifier['value'])) {
                    return false;
                }
            }
            foreach ($identifiers as $identifier) {
                if (($identifier['type'] === 'HDL' || $identifier['type'] === 'URL')  && !empty($identifier['value'])) {
                    return true;
                }
            }
        }
        return false;
    }
}
