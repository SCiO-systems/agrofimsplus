<?php

namespace App\Services\FairScoring\Rules\Interoperable;

use App\Services\FairScoring\Interfaces\FairScoreRule;
use App\Services\FairScoring\Rules\BaseRule;

class DatasetHasAnnotatedData extends BaseRule implements FairScoreRule
{
    public static $metadataCondition = 'The data included in the DATASET are annotated and/or carry a legend clarifying their meaning';
    public static $scoring = '1.5 points';
    public static $recommendation = 'Produce an annotated version of the described dataset';
    public static $anchor = 'resource-files';

    public static function calculateScore($metadataRecord)
    {
        return self::meetsCondition($metadataRecord) ? 1.5 : 0;
    }

    public static function meetsCondition($metadataRecord)
    {
        return false;
    }
}
