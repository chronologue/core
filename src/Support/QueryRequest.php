<?php

namespace Chronologue\Core\Support;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class QueryRequest extends FormRequest
{
    public function rules(): array
    {
        return [];
    }

    public function validated($key = null, $default = null): array|null|string
    {
        return $this->query($key, $default);
    }

    final public function after(): array
    {
        return [
            function (Validator $validator) {
                if (!in_array($this->query('per_page'), [10, 25, 50, 100])) {
                    $validator->errors()->add('per_page', 'Invalid per page count.');
                }
            },
        ];
    }

    final protected function failedValidation(Validator $validator): void
    {
        //
    }

    final protected function prepareForValidation(): void
    {
        $this->merge([
            'search' => $this->query('search', ''),
            'per_page' => $this->query('per_page', 10),
        ]);
    }

    final protected function passedValidation(): void
    {
        $this->replace(
            collect($this->collect())
                ->forget($this->validator->errors()->keys())
                ->all()
        );
    }
}
