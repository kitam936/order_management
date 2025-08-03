<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
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
            'pitin_date' => ['required'],
            'shop_id' => ['required','integer'],
            'car_id' => ['required','integer'],
            'items' => ['required','array'],
            'items.*.item_id' => ['required','integer'],
            'items.*.pcs' => ['required','integer','min:0'],
            'items.*.sales_price' => ['required','numeric','min:0'],
            'items.*.work_fee' => ['required','numeric','min:0'],
        ];
    }
}
