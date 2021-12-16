<?php

namespace App\Services\FairScoring\Facades;

use Illuminate\Support\Facades\Facade;

class FairScoring extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'FairScoring';
    }
}
