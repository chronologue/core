<?php

namespace Chronologue\Core\Support;

use Closure;
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

    public function breadcrumb(Closure $title, string $url): static
    {
        $this->breadcrumb[] = [$title, $url];
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
        $this->factory->share('routing.path', fn() => $this->request ? Str::start($this->request->path(), '/') : '/');
        $this->factory->share('routing.params', fn() => $this->request ? $this->request->route()->parameters() : []);
        $this->factory->share('routing.query', fn() => $this->request ? $this->request->query() : []);
        $this->factory->share('routing.group', fn() => $this->group);
        $this->factory->share('routing.breadcrumb', fn() => $this->resolveBreadcrumb());
        $this->factory->share('routing.tags', fn() => $this->tags);
        $this->factory->share('routing.title', fn() => $this->resolveTitle());

        return $this->factory->render($this->component, $this->data);
    }

    protected function resolveBreadcrumb(): array
    {
        return array_map(fn($breadcrumb) => [$breadcrumb[0](), $breadcrumb[1]], $this->breadcrumb);
    }

    protected function resolveTitle(): ?string
    {
        if (!$this->title && $this->breadcrumb) {
            return last($this->breadcrumb)[0]();
        }
        return $this->title;
    }
}