<?php

namespace App\Services\FairScoring\Interfaces;

interface FairScoreRule
{
    public static function meetsCondition($metadataRecord);
    public static function calculateScore($metadataRecord);
}
