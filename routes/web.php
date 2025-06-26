<?php

use App\Http\Controllers\BackController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::controller(FrontController::class)
    ->group(function () {
        Route::get('/', 'index');

        Route::post('/sendmail', 'sendmail');
    });

Route::controller(BackController::class)
    ->group(function () {
        Route::get('/backend', 'index');

        Route::get('/backend/delete/{id}', 'delete');
        Route::post('/backend/delete/{id}', 'deletePost');

        Route::get('/backend/edit/{id}', 'edit');
        Route::post('/backend/edit/{id}', 'editPost');

        Route::post('/backend/login', 'login');
        Route::get('/backend/logout', 'logout');

        Route::get('/backend/recupera-password', 'recuperaPassword');
        Route::post('/backend/recupera-password', 'recuperaPasswordPost');
    });

Route::controller(UsersController::class)
    ->group(function () {
        Route::get('/backend/users', 'index');

//        Route::get('/backend/users/delete/{id}', 'delete');
//        Route::post('/backend/users/delete/{id}', 'deletePost');
//
//        Route::get('/backend/users/edit/{id}', 'edit');
//        Route::post('/backend/users/edit/{id}', 'editPost');
    });
