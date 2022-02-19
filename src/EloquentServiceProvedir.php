<?php

namespace Fatihirday\Eloquent;

use Illuminate\Support\ServiceProvider;
use Fatihirday\Eloquent\Providers\Traits\CustomEloquentServiceProvider;

class EloquentServiceProvedir extends ServiceProvider
{
    use CustomEloquentServiceProvider;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->getEloquent();

        if ($this->app->runningInConsole()) {
            \Illuminate\Support\Facades\Artisan::call('ide-helper:macros');
        }
    }
}
