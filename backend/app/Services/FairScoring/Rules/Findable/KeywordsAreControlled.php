<?php

namespace App\Services\FairScoring\Rules\Findable;

use App\Services\FairScoring\Interfaces\FairScoreRule;
use App\Services\FairScoring\Rules\BaseRule;

class KeywordsAreControlled extends BaseRule implements FairScoreRule
{
    public static $metadataCondition = 'KEYWORDS in metadata are defined using controlled terms (from vocabularies, thesauri, ontologies)';
    public static $scoring = 'min( 0.25 * #TERMS, 1 ) points';
    public static $recommendation = 'Use at least 4 controlled values to describe the resource';
    public static $anchor = 'resource-keywords';

    private static function getControlledKeywordsCount($metadataRecord)
    {
        $count = 0;
        if (!empty($keywords = data_get($metadataRecord, 'keywords'))) {
            foreach ($keywords as $keyword) {
                if ($keyword['scheme'] !== 'free') $count++;
            }
        }
        return $count;
    }

    public static function calculateScore($metadataRecord)
    {
        return self::meetsCondition($metadataRecord) ? min(0.25 * self::getControlledKeywordsCount($metadataRecord), 1) : 0;
    }

    public static function meetsCondition($metadataRecord)
    {
        if (!empty($keywords = data_get($metadataRecord, 'keywords'))) {
            return self::getControlledKeywordsCount($metadataRecord) > 0 ? true : false;
        }
        return false;
    }
}
