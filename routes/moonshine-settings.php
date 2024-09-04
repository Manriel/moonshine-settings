<?php

use Illuminate\Support\Facades\Route;
use MoonShineSettings\Http\Controllers\SettingsController;

Route::group(moonshine()->configureRoutes(), static function (): void {
    Route::middleware(config('moonshine.auth.middleware', []))->group(function (): void {
        
        Route::controller(SettingsController::class)
             ->prefix('settings')
             ->as('settings.')
             ->group(function (): void {
                 Route::get('/', 'index')
                      ->name('index');
                 Route::post('/', 'store')
                      ->name('store');
             });
        
    });
});
