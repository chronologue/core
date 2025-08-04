<?php

namespace Chronologue\Core;

use Chronologue\Core\Database\Schema\Prototype;
use Chronologue\Core\Support\Controller;
use Chronologue\Core\Support\ControllerDispatcher;
use Chronologue\Core\Support\ModuleServiceProvider;
use Chronologue\Core\Support\Paginator;
use Chronologue\Core\Support\QueryRequest;
use Chronologue\Core\Support\Service;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Contracts\ControllerDispatcher as ControllerDispatcherContract;

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

    public function boot(): void
    {
        $this->bootQueryRequest();
    }

    protected function registerControllerDispatcher(): void
    {
        $this->app->singleton(ControllerDispatcherContract::class, function ($app) {
            return new ControllerDispatcher($app);
        });
    }

    protected function registerLengthAwarePaginator(): void
    {
        $this->app->bind(LengthAwarePaginator::class, Paginator::class);
    }

    protected function registerBlueprint(): void
    {
        $this->app->bind(Blueprint::class, Prototype::class);
    }

    protected function bootQueryRequest(): void
    {
        $this->app->resolving(QueryRequest::class, function (QueryRequest $request, Container $app) {
            $request = QueryRequest::createFrom($app['request'], $request);
            $request->setContainer($app);
        });
    }

    protected function resolvingController(): void
    {
        $this->app->resolving(Controller::class, function (Controller $controller, Container $app) {
            $controller->boot($app);
        });
    }

    protected function resolvingService(): void
    {
        $this->app->resolving(Service::class, function (Service $service, Container $app) {
            $service->boot($app);
        });
    }
}
