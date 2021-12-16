<?php

namespace App\Services\FairScoring\Rules\Findable;

use App\Services\FairScoring\Interfaces\FairScoreRule;
use App\Services\FairScoring\Rules\BaseRule;

class AtLeastOneUrlIsHandler extends BaseRule implements FairScoreRule
{
    public static $metadataCondition = 'At least one URL is a HANDLE';
    public static $scoring = '0.5 points additional to A ( =B )';
    public static $recommendation = '* This will be evaluated only after publishing in a repository that uses handlers';

    public static function calculateScore($metadataRecord)
    {
        return self::meetsCondition($metadataRecord) ? 0.5 : 0;
    }

    public static function meetsCondition($metadataRecord)
    {
        return false;
    }
}
