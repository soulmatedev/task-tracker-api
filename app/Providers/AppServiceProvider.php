<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot()
    {
        if (app()->runningInConsole()) {
            $this->printRoutes();
        }
    }

    private function printRoutes()
    {
        $routes = Route::getRoutes();
        foreach ($routes as $route) {
            dump([
                'method' => $route->methods(),
                'uri' => $route->uri(),
                'action' => $route->getActionName(),
            ]);
        }
    }
}
