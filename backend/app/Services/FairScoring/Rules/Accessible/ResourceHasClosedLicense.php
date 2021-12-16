<?php

namespace App\Services\FairScoring\Rules\Accessible;

use App\Services\FairScoring\Interfaces\FairScoreRule;
use App\Services\FairScoring\Rules\BaseRule;

class ResourceHasClosedLicense extends BaseRule implements FairScoreRule
{
    public static $metadataCondition = 'RESOURCE has any other standard license';
    public static $scoring = '1 point additional to A';
    public static $recommendation = 'Use License wizard to select an appropriate license';
    public static $anchor = 'resource-rights';

    public static function calculateScore($metadataRecord)
    {
        return self::meetsCondition($metadataRecord) ? 1 : 0;
    }

    public static function meetsCondition($metadataRecord)
    {
        if (!data_get($metadataRecord, 'rights') || !data_get($metadataRecord, 'rights.license')) {
            return false;
        }

        $license = data_get($metadataRecord, 'rights.license');

        $openSourceLicenses = ['GNU AGPLv3', 'GNU GPLv3', 'GNU LGPLv3', 'Mozilla Public License 2.0', 'Apache License 2.0', 'MIT License', 'Boost Software License 1.0', 'The Unlicense'];

        $doesNotUseOpenSourceLicense = !in_array($license, $openSourceLicenses);
        $usesCC0License = $license === 'CC0 1.0';
        $usesCCBYLicense = $license === 'CC BY 4.0';

        return $doesNotUseOpenSourceLicense && !$usesCC0License && !$usesCCBYLicense;
    }
}
