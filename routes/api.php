<?php

use App\Http\Controllers\Api\GuestbookController;
use Illuminate\Support\Facades\Route;

Route::controller(GuestbookController::class)
    ->group(function () {
        Route::get('/', 'index');
    });
