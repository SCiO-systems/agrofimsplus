<?php

namespace App\Services\FairScoring\Rules\Findable;

use App\Services\FairScoring\Interfaces\FairScoreRule;
use App\Services\FairScoring\Rules\BaseRule;

class ResourceHasDescription extends BaseRule implements FairScoreRule
{
    public static $metadataCondition = 'RESOURCE has DESCRIPTION defined in metadata';
    public static $scoring = '0.5 points';
    public static $recommendation = 'Provide a Resource Description';
    public static $anchor = 'resource-description';

    public static function calculateScore($metadataRecord)
    {
        return self::meetsCondition($metadataRecord) ? 0.5 : 0;
    }

    public static function meetsCondition($metadataRecord)
    {
        if (!empty($title_array = data_get($metadataRecord, 'description'))) {
            if (!empty(head($title_array)['value'])) {
                return true;
            }
        }
        return false;
    }
}
