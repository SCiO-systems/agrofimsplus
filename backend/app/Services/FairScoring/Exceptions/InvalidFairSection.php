<?php

namespace App\Services\FairScoring\Exceptions;

use Exception;

class InvalidFairSection extends Exception
{
    public function report()
    {
        \Log::error('Invalid FAIR section requested.');
    }
}
