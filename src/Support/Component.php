<?php

namespace Chronologue\Core\Support;

use Stringable;

class Component implements Stringable
{
    private string $component;
    private string $module;
    private bool $protected;

    public function __construct(string $module, string $component, bool $protected = false)
    {
        $this->component = $component;
        $this->module = $module;
        $this->protected = $protected;
    }

    public function __toString(): string
    {
        return base64_encode(json_encode([
            'module' => str_replace('.', '/', $this->module),
            'component' => str_replace('.', '/', $this->component),
            'protected' => $this->protected,
        ]));
    }
}