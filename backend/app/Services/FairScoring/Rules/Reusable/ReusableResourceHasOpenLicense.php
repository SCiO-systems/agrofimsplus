<?php

namespace App\Services\FairScoring\Rules\Reusable;

use App\Services\FairScoring\Interfaces\FairScoreRule;
use App\Services\FairScoring\Rules\BaseRule;

class ReusableResourceHasOpenLicense extends BaseRule implements FairScoreRule
{
    public static $metadataCondition = 'RESOURCE has Open Source or CC0 or CC-BY license';
    public static $scoring = '1 point';
    public static $recommendation = 'Use License wizard to select an appropriate license';

    public static function calculateScore($metadataRecord)
    {
        return self::meetsCondition($metadataRecord) ? 1 : 0;
    }

    public static function meetsCondition($metadataRecord)
    {
        $license = data_get($metadataRecord, 'rights.license');

        $openSourceLicenses = ['GNU AGPLv3', 'GNU GPLv3', 'GNU LGPLv3', 'Mozilla Public License 2.0', 'Apache License 2.0', 'MIT License', 'Boost Software License 1.0', 'The Unlicense'];

        $usesOpenSourceLicense = in_array($license, $openSourceLicenses);
        $usesCC0License = $license === 'CC0 1.0';
        $usesCCBYLicense = $license === 'CC BY 4.0';

        return $usesOpenSourceLicense ||  $usesCC0License || $usesCCBYLicense;
    }
}
