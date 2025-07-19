<?php

namespace Chronologue\Core\Support;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\ResolvesRouteDependencies;

abstract class Controller
{
    use ResolvesRouteDependencies;

    protected Redirector $redirector;
    protected ResponseFactory $response;
    protected UrlGenerator $url;
    protected ResponseBuilder $builder;

    /**
     * @throws BindingResolutionException
     */
    public function boot(Container $app): void
    {
        $this->redirector = $app->make(Redirector::class);
        $this->response = $app->make(ResponseFactory::class);
        $this->url = $app->make(UrlGenerator::class);
        $this->builder = $app->make(ResponseBuilder::class);
    }

    protected function redirector(): Redirector
    {
        return $this->redirector;
    }

    protected function response(): ResponseFactory
    {
        return $this->response;
    }

    protected function url(): UrlGenerator
    {
        return $this->url;
    }

    protected function builder(?Request $request = null): ResponseBuilder
    {
        return tap($this->builder, function (ResponseBuilder $builder) use ($request) {
            $builder->initialize();
            $builder->request($request);
        });
    }
}