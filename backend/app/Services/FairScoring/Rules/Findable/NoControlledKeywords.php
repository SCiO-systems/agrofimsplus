<?php

namespace App\Services\FairScoring\Rules\Findable;

use App\Services\FairScoring\Interfaces\FairScoreRule;
use App\Services\FairScoring\Rules\BaseRule;

class NoControlledKeywords extends BaseRule implements FairScoreRule
{
    public static $metadataCondition = 'If no controlled term is used in KEYWORDS';
    public static $scoring = 'min( 0.0625 * #KEYWORDS, 0.25 ) points';
    public static $recommendation = 'if no controlled value  is used then provide at least 4 keywords to describe the resource';
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

    private static function getFreeKeywordsCount($metadataRecord)
    {
        $count = 0;
        if (!empty($keywords = data_get($metadataRecord, 'keywords'))) {
            foreach ($keywords as $keyword) {
                if ($keyword['scheme'] === 'free') $count++;
            }
        }
        return $count;
    }

    public static function calculateScore($metadataRecord)
    {
        return self::meetsCondition($metadataRecord) ? min(0.0625 * self::getFreeKeywordsCount($metadataRecord), 0.25) : 0;
    }

    public static function meetsCondition($metadataRecord)
    {
        if (!empty($keywords = data_get($metadataRecord, 'keywords'))) {
            return self::getControlledKeywordsCount($metadataRecord) > 0 ? false : true;
        }
        return false;
    }
}
