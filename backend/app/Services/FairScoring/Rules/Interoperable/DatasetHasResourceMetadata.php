<?php

namespace App\Services\FairScoring\Rules\Interoperable;

use App\Services\FairScoring\Interfaces\FairScoreRule;
use App\Services\FairScoring\Rules\BaseRule;

class DatasetHasResourceMetadata extends BaseRule implements FairScoreRule
{
    public static $metadataCondition = 'DATASET is linked to relevant PUBLICATIONS to provide context';
    public static $scoring = '1.5 points';
    public static $recommendation = 'Provide in Metadata links from datasets to relevant publications or vice versa';
    public static $anchor = 'resource-related-resources';

    public static function calculateScore($metadataRecord)
    {
        return self::meetsCondition($metadataRecord) ? 1.5 : 0;
    }

    public static function meetsCondition($metadataRecord)
    {
        return !empty(data_get($metadataRecord, 'related_resources'));
    }
}
