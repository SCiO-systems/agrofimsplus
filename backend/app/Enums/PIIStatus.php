<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PIIStatus extends Enum
{
    const PENDING = 'pending';
    const PASSED = 'passed';
    const FAILED = 'failed';
    const TIMEOUT = 'timeout';
}
