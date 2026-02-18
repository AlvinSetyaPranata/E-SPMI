<?php

namespace App\Modules\Analytics\Providers;

use Illuminate\Support\ServiceProvider;

class AnalyticsModuleServiceProvider extends ServiceProvider
{
    /**
     * Module name
     */
    protected string $moduleName = 'Analytics';

    /**
     * Module path
     */
    protected string $modulePath;

    /**
     * Constructor
     */
    public function __construct($app)
    {
        parent::__construct($app);
        $this->modulePath = app_path('Modules/Analytics');
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        // Register services here
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(database_path('migrations/analytics'));
        
        // Load routes if exists
        $routeFile = $this->modulePath . '/routes/web.php';
        if (file_exists($routeFile)) {
            $this->loadRoutesFrom($routeFile);
        }
        
        // Load views if exists
        $viewsPath = $this->modulePath . '/resources/views';
        if (is_dir($viewsPath)) {
            $this->loadViewsFrom($viewsPath, 'analytics');
        }
    }
}
