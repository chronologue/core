<?php

namespace Chronologue\Core\Support;

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
        $this->booting(function () {
            $this->bootMigrations();
        });

        $this->booted(function () {
            $this->bootRoutes();
        });
    }

    protected function bootMigrations(): void
    {
        if ($this->app->runningInConsole()) {
            if (realpath($directory = $this->getPath($this->getMigrationPath())) !== false) {
                $this->loadMigrationsFrom($directory);
            }
        }
    }

    protected function bootRoutes(): void
    {
        if (!$this->routesAreCached() && $this->shouldLoadRoutes()) {
            $this->loadRoutes();
        }
    }

    protected function routesAreCached(): bool
    {
        return $this->app->routesAreCached();
    }

    protected function shouldLoadRoutes(): bool
    {
        return true;
    }

    protected function loadRoutes(): void
    {
        $this->app->call(function () {
            if (realpath($apiRoute = $this->getPath($this->getApiRouteFile())) !== false) {
                Route::middleware('api')
                    ->prefix($this->getApiRoutePrefix())
                    ->name($this->getApiRouteName())
                    ->group($apiRoute);
            }

            if (realpath($web = $this->getPath($this->getRouteFile())) !== false) {
                Route::middleware('web')->group($web);
            }
        });
    }

    protected function getRouteFile(): string
    {
        return 'routes.php';
    }

    protected function getApiRouteFile(): string
    {
        return 'routes-api.php';
    }

    protected function getApiRoutePrefix(): string
    {
        return 'api';
    }

    protected function getApiRouteName(): string
    {
        return 'api.';
    }

    protected function getMigrationPath(): string
    {
        return 'Database/Migrations';
    }

    protected function getPath(string $path = ''): string
    {
        return $this->app->joinPaths($this->getDirectory(), $path);
    }

    protected function getDirectory(): string
    {
        return $this->directory;
    }

    protected function initDirectory(): void
    {
        $class = new ReflectionClass($this);
        $this->directory = dirname($class->getFileName());
    }
}
