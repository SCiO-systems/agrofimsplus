<?php

namespace App\Services\FairScoring\Rules\Accessible;

use App\Services\FairScoring\Interfaces\FairScoreRule;
use App\Services\FairScoring\Rules\BaseRule;

class DatasetHasOpenLicense extends BaseRule implements FairScoreRule
{
    public static $metadataCondition = 'DATASET has Open Source or CC0 or CC-BY license';
    public static $scoring = '2 points additional to A';
    public static $recommendation = 'Use License wizard to select an appropriate license';
    public static $anchor = 'resource-rights';

    public static function calculateScore($metadataRecord)
    {
        return self::meetsCondition($metadataRecord) ? 2 : 0;
    }

    public static function meetsCondition($metadataRecord)
    {
        if (!data_get($metadataRecord, 'rights') || !data_get($metadataRecord, 'rights.license')) {
            return false;
        }

        $license = data_get($metadataRecord, 'rights.license');

        $openSourceLicenses = ['GNU AGPLv3', 'GNU GPLv3', 'GNU LGPLv3', 'Mozilla Public License 2.0', 'Apache License 2.0', 'MIT License', 'Boost Software License 1.0', 'The Unlicense'];

        $usesOpenSourceLicense = in_array($license, $openSourceLicenses);
        $usesCC0License = $license === 'CC0 1.0';
        $usesCCBYLicense = $license === 'CC BY 4.0';

        return $usesOpenSourceLicense ||  $usesCC0License || $usesCCBYLicense;
    }
}
