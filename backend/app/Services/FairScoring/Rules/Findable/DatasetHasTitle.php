<?php

namespace App\Services\FairScoring\Rules\Findable;

use App\Services\FairScoring\Interfaces\FairScoreRule;
use App\Services\FairScoring\Rules\BaseRule;

class DatasetHasTitle extends BaseRule implements FairScoreRule
{
    public static $metadataCondition = 'DATASET has TITLE defined in metadata';
    public static $scoring = '0.125 points';
    public static $recommendation = 'Provide a Resource Title';
    public static $anchor = 'resource-title';

    public static function calculateScore($metadataRecord)
    {
        return self::meetsCondition($metadataRecord) ? 0.125 : 0;
    }

    public static function meetsCondition($metadataRecord)
    {
        if (!empty($title_array = data_get($metadataRecord, 'title'))) {
            if (!empty(head($title_array)['value'])) {
                return true;
            }
        }
        return false;
    }
}
