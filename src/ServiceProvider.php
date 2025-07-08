<?php

namespace Chronologue\Core;

use Chronologue\Core\Database\Schema\Prototype;
use Chronologue\Core\Support\Controller;
use Chronologue\Core\Support\ControllerInvoker;
use Chronologue\Core\Support\ModuleServiceProvider;
use Chronologue\Core\Support\Paginator;
use Chronologue\Core\Support\Service;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Contracts\ControllerDispatcher;

class ServiceProvider extends ModuleServiceProvider
{
    public function register(): void
    {
        parent::register();
        
        $this->registerControllerDispatcher();
        $this->registerLengthAwarePaginator();
        $this->registerBlueprint();
        $this->resolvingController();
        $this->resolvingService();
    }

    protected function registerControllerDispatcher():void
    {
        $this->app->singleton(ControllerDispatcher::class, function ($app) {
            return new ControllerInvoker($app);
        });
    }

    protected function registerLengthAwarePaginator():void
    {
        $this->app->bind(LengthAwarePaginator::class, Paginator::class);
    }

    protected function registerBlueprint():void
    {
        $this->app->bind(Blueprint::class, Prototype::class);
    }

    protected function resolvingController():void
    {
        $this->app->resolving(Controller::class, function (Controller $controller, Application $app) {
            $controller->boot($app);
        });
    }

    protected function resolvingService():void
    {
        $this->app->resolving(Service::class, function (Service $service, Application $app) {
            $service->boot($app);
        });
    }
}
