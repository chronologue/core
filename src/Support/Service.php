<?php

namespace Chronologue\Core\Support;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\ConnectionInterface;

abstract class Service
{
    protected Dispatcher $event;
    protected Repository $cache;
    protected ConnectionInterface $connection;

    /**
     * @throws BindingResolutionException
     */
    public function boot(Container $app): void
    {
        $this->event = $app->make(Dispatcher::class);
        $this->cache = $app->make(Repository::class);
        $this->connection = $app->make(ConnectionInterface::class);
    }

    protected function event(): Dispatcher
    {
        return $this->event;
    }

    protected function cache(): Repository
    {
        return $this->cache;
    }

    protected function connection(): ConnectionInterface
    {
        return $this->connection;
    }
}