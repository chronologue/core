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

        $class = new ReflectionClass($this);
        $this->directory = dirname($class->getFileName());
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
            $directory = str_replace('/', DIRECTORY_SEPARATOR, $this->directory . '/Database/Migrations');
            if (is_dir($directory)) {
                $this->loadMigrationsFrom($directory);
            }
        }
    }

    protected function bootRoutes(): void
    {
        if (!($this->app instanceof CachesRoutes && $this->app->routesAreCached())) {
            foreach ((array)config('module.routes') as $key => $value) {
                if (file_exists($route = str_replace('/', DIRECTORY_SEPARATOR, $this->directory . '/' . $value['name']))) {
                    Route::middleware($key)
                        ->prefix($value['prefix'])
                        ->group($route);
                }
            }
        }
    }
}
