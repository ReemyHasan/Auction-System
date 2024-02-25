<?php

use Illuminate\Support\Facades\Route;

Route::group(["middleware" => ["admin", "auth"]], function () {
    Route::get('/run-migrations', function () {
        if (Artisan::call('migrate', ["--force" => true]) === 0)
            return "migrations are done";
    });
    Route::get('/clear-cache', function () {
        if (Artisan::call('cache:clear') === 0)
            return "Application cache cleared successfully";
    });
});
