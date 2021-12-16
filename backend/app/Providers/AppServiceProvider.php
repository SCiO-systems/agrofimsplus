<?php

namespace App\Providers;

use App;
use App\Services\FairScoring\FairScoring;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('FairScoring', function () {
            return new FairScoring;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
