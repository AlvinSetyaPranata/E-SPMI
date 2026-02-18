<?php

namespace App\Modules\Pelaksanaan\Providers;

use Illuminate\Support\ServiceProvider;

class PelaksanaanModuleServiceProvider extends ServiceProvider
{
    /**
     * Module name
     */
    protected string $moduleName = 'Pelaksanaan';

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
        $this->modulePath = app_path('Modules/Pelaksanaan');
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
        $this->loadMigrationsFrom(database_path('migrations/pelaksanaan'));
        
        // Load routes if exists
        $routeFile = $this->modulePath . '/routes/web.php';
        if (file_exists($routeFile)) {
            $this->loadRoutesFrom($routeFile);
        }
        
        // Load views if exists
        $viewsPath = $this->modulePath . '/resources/views';
        if (is_dir($viewsPath)) {
            $this->loadViewsFrom($viewsPath, 'pelaksanaan');
        }
    }
}
