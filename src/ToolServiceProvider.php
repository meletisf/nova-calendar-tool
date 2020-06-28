<?php

namespace Czemu\NovaCalendarTool;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Czemu\NovaCalendarTool\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'nova-calendar-tool');

        $this->publishes([
            __DIR__ . '/../config/nova-calendar.php' => config_path('nova-calendar.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../database/migrations/create_events_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_events_table.php'),
        ], 'migrations');

        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            //
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
                ->prefix('nova-vendor/nova-calendar-tool')
                ->namespace('Czemu\NovaCalendarTool\Http\Controllers')
                ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}