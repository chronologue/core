<?php

namespace Chronologue\Core\Support;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Tappable;
use Inertia\ResponseFactory;

class ResponseBuilder
{
    use Tappable;

    private ResponseFactory $factory;
    private ?Request $request;
    private string $component;
    private array $data;
    private array $group;
    private array $breadcrumb;
    private array $tags;
    private ?string $title;

    public function __construct(ResponseFactory $factory)
    {
        $this->factory = $factory;
        $this->initialize();
    }

    public function initialize(): void
    {
        $this->request = null;
        $this->component = '';
        $this->data = [];
        $this->group = [];
        $this->breadcrumb = [];
        $this->tags = [];
        $this->title = null;
    }

    public function group(string|array $group): static
    {
        $this->group = is_string($group) ? [$group] : $group;
        return $this;
    }

    public function tags(array $tags): static
    {
        $this->tags = $tags;
        return $this;
    }

    public function breadcrumb(string|array $title, ?string $url = null): static
    {
        if (is_string($title)) {
            $this->breadcrumb[] = [$title, ($url ?: $this->request?->url()) ?: '/'];
        } else {
            $this->breadcrumb[] = $title;
        }

        return $this;
    }

    public function request(?Request $request): static
    {
        $this->request = $request;
        return $this;
    }

    public function component(string $component): static
    {
        $this->component = $component;
        return $this;
    }

    public function title(?string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function data(string $key, mixed $value): static
    {
        $this->data[$key] = $value;
        return $this;
    }

    public function factory(): ResponseFactory
    {
        return $this->factory;
    }

    public function build(): Responsable
    {
        if ($request = $this->request) {
            $this->factory->share('routing.path', fn() => Str::start($request->path(), '/'));
            $this->factory->share('routing.params', fn() => $request->route()->parameters);
            $this->factory->share('routing.query', fn() => $request->query());
        }

        $this->factory->share('routing.group', fn() => $this->group);
        $this->factory->share('routing.breadcrumb', fn() => $this->breadcrumb);
        $this->factory->share('routing.tags', fn() => $this->tags);
        $this->factory->share('routing.title', function () {
            return (!$this->title && $this->breadcrumb)
                ? last($this->breadcrumb)[0]
                : $this->title;
        });

        return $this->factory->render($this->component, $this->data);
    }
}