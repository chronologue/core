<?php

namespace Chronologue\Core\Support;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;

class QueryRequest extends Request implements ValidatesWhenResolved
{
    protected Container $container;

    public function rules(): array
    {
        return [];
    }

    public function after(): array
    {
        return [];
    }

    public function validated($key = null, $default = null): array|null|string
    {
        return $this->query($key, $default);
    }

    public function setContainer(Container $container): static
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @throws BindingResolutionException
     */
    public function validateResolved(): void
    {
        $this->merge([
            'search' => $this->query('search', ''),
            'per_page' => $this->query('per_page', 10),
        ]);

        $instance = $this->getValidatorInstance();
        $instance->fails();

        $this->replace(
            collect($this->collect())
                ->forget($instance->errors()->keys())
                ->all()
        );
    }

    /**
     * @throws BindingResolutionException
     */
    protected function getValidatorInstance(): Validator
    {
        $factory = $this->container->make(Factory::class);
        $validator = $factory->make($this->query(), $this->rules())->stopOnFirstFailure(false);

        $validator->after(function (Validator $validator) {
            if (!in_array($this->query('per_page'), [10, 25, 50, 100])) {
                $validator->errors()->add('per_page', 'Invalid per page count.');
            }
        });

        if (!empty($after = $this->after())) {
            $validator->after($after);
        }

        return $validator;
    }
}
