<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Register module service providers
        $this->app->register(\App\Modules\Core\Providers\CoreModuleServiceProvider::class);
        $this->app->register(\App\Modules\Standar\Providers\StandarModuleServiceProvider::class);
        $this->app->register(\App\Modules\Audit\Providers\AuditModuleServiceProvider::class);
        $this->app->register(\App\Modules\Pelaksanaan\Providers\PelaksanaanModuleServiceProvider::class);
        $this->app->register(\App\Modules\Pengendalian\Providers\PengendalianModuleServiceProvider::class);
        $this->app->register(\App\Modules\Analytics\Providers\AnalyticsModuleServiceProvider::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
