<?php

namespace Chronologue\Core\Support;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;

abstract class Service
{
    protected Dispatcher $event;
    protected Repository $cache;

    /**
     * @throws BindingResolutionException
     */
    public function boot(Application $app): void
    {
        $this->event = $app->make(Dispatcher::class);
        $this->cache = $app->make(Repository::class);
    }

    protected function event(): Dispatcher
    {
        return $this->event;
    }

    protected function cache(): Repository
    {
        return $this->cache;
    }
}