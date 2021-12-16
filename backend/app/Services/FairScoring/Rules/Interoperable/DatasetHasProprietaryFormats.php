<?php

namespace App\Services\FairScoring\Rules\Interoperable;

use App\Services\FairScoring\Interfaces\FairScoreRule;
use App\Services\FairScoring\Rules\BaseRule;

class DatasetHasProprietaryFormats extends BaseRule implements FairScoreRule
{
    public static $metadataCondition = 'if not, DATASET files use formats that are proprietary, but can be recognized and used by freely available tools';
    public static $scoring = '0.5 points';
    public static $recommendation = 'Avoid using proprietary formats when possible';
    public static $anchor = 'resource-files';

    public static function calculateScore($metadataRecord)
    {
        return self::meetsCondition($metadataRecord) ? 0.5 : 0;
    }

    public static function meetsCondition($metadataRecord)
    {
        if (!empty($resource_files = data_get($metadataRecord, 'resource_files'))) {
            foreach ($resource_files as $file) {
                if (array_key_exists('mime_type', $file) && empty($file['mime_type'])) {
                    return true;
                }
            }
        }
        return false;
    }
}
