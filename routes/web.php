<?php

use App\Http\Controllers\BackController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\Authenticator;
use Illuminate\Support\Facades\Route;

Route::controller(FrontController::class)
    ->group(function () {
        Route::get('/', 'index');

        Route::post('/sendmail', 'sendmail');
    });

Route::controller(BackController::class)
    ->group(function () {
        Route::get('/backend', 'index');

        Route::post('/backend/login', 'login');
        Route::get('/backend/logout', 'logout');

        Route::get('/backend/recupera-password', 'recuperaPassword');
        Route::post('/backend/recupera-password', 'recuperaPasswordPost');

        // Imposto il middleware di veridica dell'autenticazione su un gruppo di route
        Route::middleware(Authenticator::class)
            ->group(function () {
                Route::get('/backend/delete/{id}', 'delete');
                Route::post('/backend/delete/{id}', 'deletePost');

                Route::get('/backend/edit/{id}', 'edit');
                Route::post('/backend/edit/{id}', 'editPost');
            });
    });

Route::controller(BackupController::class)
    // Imposto il middleware di veridica dell'autenticazione sull'intero controller
    ->middleware(Authenticator::class)
    ->group(function () {
        Route::get('/backend/backup', 'index');

        Route::post('/backend/backup/delete', 'deleteMultiple');

        Route::get('/backend/backup/delete/{filename}', 'delete')
            ->where('filename', '.*');

        Route::get('/backend/backup/dwl/{filename}', 'dwl')
            ->where('filename', '.*');
    });

Route::controller(UsersController::class)
    // Imposto il middleware di veridica dell'autenticazione sull'intero controller
    ->middleware(Authenticator::class)
    ->group(function () {
        Route::get('/backend/users', 'index');

//        Route::get('/backend/users/delete/{id}', 'delete');
//        Route::post('/backend/users/delete/{id}', 'deletePost');
//
//        Route::get('/backend/users/edit/{id}', 'edit');
//        Route::post('/backend/users/edit/{id}', 'editPost');
    });
