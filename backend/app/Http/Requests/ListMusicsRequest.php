<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListMusicsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "limit" => "sometimes|integer|min:1|max:100",
            "page" => "sometimes|integer|min:1",
            "orderBy" => "sometimes|string|in:views,created_at",
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            "page" => $this->input("page", 1),
            "limit" => $this->input("limit", 5),
            "approved" => filter_var(
                $this->input("approved", true),
                FILTER_VALIDATE_BOOLEAN
            ),
        ]);
    }
}
