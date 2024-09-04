<?php

namespace MoonShineSettings;

use Illuminate\Support\ServiceProvider;

class MoonShineSettingsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(dirname(__DIR__, 1) .'/config/moonshine/settings.php', 'moonshine.settings');
    }
    
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(dirname(__DIR__,1).'/database/migrations');
        
        $this->loadTranslationsFrom(dirname(__DIR__,1).'/lang', 'moonshine-settings');
        
        $this->loadRoutesFrom(dirname(__DIR__, 1).'/routes/moonshine-settings.php');
        
        $this->publishes([
            dirname(__DIR__,1).'/stubs/config/moonshine/settings.php' => config_path('/moonshine/settings.php')
        ], 'config');
    }
}
