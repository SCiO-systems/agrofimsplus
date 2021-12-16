<?php

namespace App\Services\FairScoring\Rules\Interoperable;

use App\Services\FairScoring\Interfaces\FairScoreRule;
use App\Services\FairScoring\Rules\BaseRule;

class ResourceHasAnnotatedData extends BaseRule implements FairScoreRule
{
    public static $metadataCondition = '"The data included in the resource are annotated and/or carry a legend clarifying their meaning"';
    public static $scoring = '1 point';
    public static $recommendation = 'Produce an annotated version of the described dataset';
    public static $anchor = 'resource-files';

    public static function calculateScore($metadataRecord)
    {
        return self::meetsCondition($metadataRecord) ? 1 : 0;
    }

    public static function meetsCondition($metadataRecord)
    {
        return true;
    }
}
