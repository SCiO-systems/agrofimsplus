<?php

namespace App\Services\FairScoring\Exceptions;

use Exception;

class InvalidDataException extends Exception
{
    public function report()
    {
        \Log::error('Data provided for Fair Scoring is either empy or invalid.');
    }
}
