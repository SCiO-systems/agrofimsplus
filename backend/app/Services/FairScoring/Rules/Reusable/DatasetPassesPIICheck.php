<?php

namespace App\Services\FairScoring\Rules\Reusable;

use App\Enums\PIIStatus;
use App\Services\FairScoring\Interfaces\FairScoreRule;
use App\Services\FairScoring\Rules\BaseRule;

class DatasetPassesPIICheck extends BaseRule implements FairScoreRule
{
    public static $metadataCondition = 'DATASET complies with basic Personal Information Protection principles';
    public static $scoring = '2 points';
    public static $recommendation = 'Run the PII check service and accordingly handle the reported PII issues';
    public static $anchor = 'resource-files';

    public static function calculateScore($metadataRecord)
    {
        return self::meetsCondition($metadataRecord) ? 2 : 0;
    }

    public static function meetsCondition($metadataRecord)
    {
        $files = data_get($metadataRecord, 'resource_files');

        if (empty($files)) {
            return false;
        }

        foreach ($files as $file) {
            $passesCheck = $file['pii_check'] === PIIStatus::PASSED;
            if (!$passesCheck) {
                return false;
            }
        }

        return false;
    }
}
