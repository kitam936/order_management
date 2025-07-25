<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'item_category_id' => ['required', 'integer', 'exists:item_categories,id'],
            'prod_code' => ['required', 'string', 'max:25', ],
            'item_name' => ['required', 'string', 'max:50', ],
            'item_info' => ['max:255', 'nullable'],
            'item_price' => ['numeric', 'min:0', 'nullable'],
            'item_cost' => ['numeric', 'min:0', 'nullable'],
            'item_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:3048'], // 修正箇所
            ];
    }
}
