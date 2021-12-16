<?php

namespace App\Services\FairScoring\Rules\Interoperable;

use App\Services\FairScoring\Interfaces\FairScoreRule;
use App\Services\FairScoring\Rules\BaseRule;

class ResourceHasAnnotatedControlledValues extends BaseRule implements FairScoreRule
{
    public static $metadataCondition = '"The annotations are built using controlled values (defined in a vocabulary, thesaurus or ontology)"';
    public static $scoring = '1 point';
    public static $recommendation = 'Use controlled values for annotating the data included in the dataset';

    public static function calculateScore($metadataRecord)
    {
        return self::meetsCondition($metadataRecord) ? 1 : 0;
    }

    public static function meetsCondition($metadataRecord)
    {
        return false;
    }
}
