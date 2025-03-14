<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ADRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('ad'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $find = strpos($this->route()->action["controller"], '@');
        $method = substr($this->route()->action["controller"], $find + 1);
        return match ($method) {
            'index' => [
                'search' => 'nullable|string',
                'categories' => 'nullable|array',
                'tags' => 'nullable|array',
                'regions' => 'nullable|array',
                'price_from' => 'nullable|numeric|decimal:2|required_with:price_to',
                'price_to' => 'nullable|numeric|decimal:2|required_with:price_from',
                'date_from' => 'nullable|date_format:Y-m-d|after_or_equal:today|required_with:date_to',
                'date_to' => 'nullable|date_format:Y-m-d|after:date_from|required_with:date_from',
                'order_column' => 'nullable|string|in:price,views,created_at|required_with:order_type',
                'order_type' => 'nullable|string|in:asc,desc|required_with:order_column',
                'per_page' => 'nullable|integer',
            ],
            'store' => [
                'title' => 'required|string|max:100',
                'description' => 'required|string',
                'image' => 'required|image|mimes:jpeg,png,jpg',
                'price' => 'required|string',
                'categories' => 'required|array',
                'regions' => 'required|array',
                'tags' => 'required|array',
                'categories.*' => 'required|integer|exists:categories,id',
                'tags.*' => 'required|integer|exists:tags,id',
                'regions.*' => 'required|integer|exists:regions,id',
            ],
            'update' => [
                'title' => 'nullable|string|max:100',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg',
                'price' => 'nullable|string',
                'categories' => 'nullable|array',
                'regions' => 'nullable|array',
                'tags' => 'nullable|array',
                'categories.*' => 'nullable|integer|exists:categories,id',
                'tags.*' => 'nullable|integer|exists:tags,id',
                'regions.*' => 'nullable|integer|exists:regions,id',
            ],
        };
    }
}
