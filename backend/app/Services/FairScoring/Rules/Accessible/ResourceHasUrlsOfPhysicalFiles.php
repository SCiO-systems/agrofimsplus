<?php

namespace App\Services\FairScoring\Rules\Accessible;

use App\Services\FairScoring\Interfaces\FairScoreRule;
use App\Services\FairScoring\Rules\BaseRule;

class ResourceHasUrlsOfPhysicalFiles extends BaseRule implements FairScoreRule
{
    public static $metadataCondition = 'URLs of physical files are provided in metadata';
    public static $scoring = '2 points';
    public static $recommendation = 'Provide physical files or relevant URLs';
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

        return true;
    }
}
