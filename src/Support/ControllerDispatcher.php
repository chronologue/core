<?php

namespace Chronologue\Core\Support;

use Chronologue\Core\Support\Attributes\Transaction;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Routing\ControllerDispatcher as BaseControllerDispatcher;
use Illuminate\Routing\Route;
use ReflectionMethod;
use Throwable;

class ControllerDispatcher extends BaseControllerDispatcher
{
    /**
     * @throws Throwable
     */
    public function dispatch(Route $route, $controller, $method)
    {
        if (!(new ReflectionMethod($controller, $method))->getAttributes(Transaction::class)) {
            return parent::dispatch($route, $controller, $method);
        }

        $parameters = $this->resolveParameters($route, $controller, $method);

        /** @var ConnectionInterface $connection */
        $connection = $this->container->get(ConnectionInterface::class);

        return $connection->transaction(function () use ($controller, $method, $parameters) {
            if (method_exists($controller, 'callAction')) {
                return $controller->callAction($method, $parameters);
            }
            return $controller->{$method}(...array_values($parameters));
        });
    }
}
