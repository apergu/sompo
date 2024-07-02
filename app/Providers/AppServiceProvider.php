<?php

namespace App\Providers;

use App\Database\Connectors\CustomSqlServerConnector;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind('db.connector.sqlsrv',CustomSqlServerConnector::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
