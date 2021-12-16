<?php

namespace App\Services\FairScoring\Enums;

use BenSampo\Enum\Enum;

/**
 * The available FAIR sections
 */
final class FairSection extends Enum
{
    const FINDABLE = 'findable';
    const ACCESSIBLE = 'accessible';
    const INTEROPERABLE = 'interoperable';
    const REUSABLE = 'reusable';
}
