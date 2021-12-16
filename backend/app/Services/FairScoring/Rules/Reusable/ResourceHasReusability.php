<?php

namespace App\Services\FairScoring\Rules\Reusable;

use App\Services\FairScoring\Facades\FairScoring;
use App\Services\FairScoring\Interfaces\FairScoreRule;
use App\Services\FairScoring\Rules\BaseRule;

class ResourceHasReusability extends BaseRule implements FairScoreRule
{
    public static $metadataCondition = 'Reusability is directly linked to its Findability, Accessibility and Interoperability qualities';
    public static $scoring = 'max 2 points calculated as (F + A + I) / 7.5';
    public static $recommendation = 'Improve Findability, Accessibility and / or Interoperability';

    public static function calculateScore($metadataRecord)
    {
        $fairScoreService = FairScoring::forRecord($metadataRecord);
        $findableScore = $fairScoreService->calculateFindableScore();
        $accessibleScore = $fairScoreService->calculateAccessibleScore();
        $interoperableScore = $fairScoreService->calculateInteroperableScore();
        $score = ($findableScore + $accessibleScore + $interoperableScore) / 7.5;

        // Max points provided.
        $score = $score > 2 ? 2 : round($score, 1);

        return self::meetsCondition($metadataRecord) ? $score : 0;
    }

    public static function meetsCondition($metadataRecord)
    {
        return true;
    }
}
