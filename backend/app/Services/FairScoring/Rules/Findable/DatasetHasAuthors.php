<?php

namespace App\Services\FairScoring\Rules\Findable;

use App\Services\FairScoring\Interfaces\FairScoreRule;
use App\Services\FairScoring\Rules\BaseRule;

class DatasetHasAuthors extends BaseRule implements FairScoreRule
{
    public static $metadataCondition = 'DATASET has AUTHORS defined in metadata';
    public static $scoring = '0.125 points ( =C )';
    public static $recommendation = 'Define the Resource Authors';
    public static $anchor = 'resource-authors';

    public static function calculateScore($metadataRecord)
    {
        return self::meetsCondition($metadataRecord) ? 0.125 : 0;
    }

    public static function meetsCondition($metadataRecord)
    {
        if (!empty($authors_array = data_get($metadataRecord, 'authors'))) {
            if (!empty(head($authors_array)['full_name'])) {
                return true;
            }
        }
        return false;
    }
}
