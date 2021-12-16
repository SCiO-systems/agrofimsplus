<?php

namespace App\Services\FairScoring\Rules\Findable;

use App\Services\FairScoring\Interfaces\FairScoreRule;
use App\Services\FairScoring\Rules\BaseRule;

class AtLeastOneAuthorVerified extends BaseRule implements FairScoreRule
{
    public static $metadataCondition = 'At least one AUTHOR is defined using ORCID or RORid';
    public static $scoring = '0.5 points additional to C';
    public static $recommendation = 'Use ORCIDs for individuals and/or ROR ids for organisations';
    public static $anchor = 'resource-authors';

    public static function calculateScore($metadataRecord)
    {
        return self::meetsCondition($metadataRecord) ? 0.5 : 0;
    }

    public static function meetsCondition($metadataRecord)
    {
        if (!empty($authors_array = data_get($metadataRecord, 'authors'))) {
            foreach ($authors_array as $author) {
                if (!empty($author['agent_ids'])) {
                    return true;
                }
            }
        }
        return false;
    }
}
