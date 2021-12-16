<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

// Default fallback route.
Route::fallback([Controller::class, 'default']);
