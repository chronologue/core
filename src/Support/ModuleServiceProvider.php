<?php

namespace Chronologue\Core\Support;

use Illuminate\Contracts\Foundation\CachesRoutes;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use ReflectionClass;

abstract class ModuleServiceProvider extends ServiceProvider
{
    protected string $directory;

    public function __construct($app)
    {
        parent::__construct($app);
        $this->initDirectory();
    }

    public function register(): void
    {
        $this->registering();

        $this->booting(function () {
            $this->bootingBefore();
            $this->bootRoutes();
            $this->bootMigrations();
            $this->bootingAfter();
        });
    }

    protected function registering(): void
    {
        //
    }

    protected function bootingBefore(): void
    {
        //
    }

    protected function bootingAfter(): void
    {
        //
    }

    protected function bootMigrations(): void
    {
        if ($this->app->runningInConsole()) {
            $directory = str_replace('/', DIRECTORY_SEPARATOR, $this->getDirectory() . '/Database/Migrations');
            if (is_dir($directory)) {
                $this->loadMigrationsFrom($directory);
            }
        }
    }

    protected function bootRoutes(): void
    {
        $this->bootRoutePatterns();

        if (!($this->app instanceof CachesRoutes && $this->app->routesAreCached())) {
            foreach ((array)config('module.routes') as $key => $value) {
                if (file_exists($route = str_replace('/', DIRECTORY_SEPARATOR, $this->getDirectory() . '/' . $value['name']))) {
                    Route::middleware($key)
                        ->prefix($value['prefix'])
                        ->group($route);
                }
            }
        }
    }

    protected function bootRoutePatterns(): void
    {
        if ($patterns = $this->routePatterns()) {
            Route::patterns($patterns);
        }
    }

    protected function routePatterns(): array
    {
        return [];
    }

    protected function initDirectory(): void
    {
        $class = new ReflectionClass($this);
        $this->directory = dirname($class->getFileName());
    }

    protected function getDirectory(): string
    {
        return $this->directory;
    }
}
