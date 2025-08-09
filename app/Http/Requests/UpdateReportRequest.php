<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportRequest extends FormRequest
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
            'detail_id' => ['required','integer'],
            'title' => ['required', 'string', 'max:30', ],
            'report' => ['max:255', 'required'],
            'image1'=>['image','mimes:jpg,jpeg,png,JPG,JPEG,PNG|max:3048', 'nullable'],
            'image2'=>['image','mimes:jpg,jpeg,png,JPG,JPEG,PNG|max:3048', 'nullable'],
            'image3'=>['image','mimes:jpg,jpeg,png,JPG,JPEG,PNG|max:3048', 'nullable'],
            'image4'=>['image','mimes:jpg,jpeg,png,JPG,JPEG,PNG|max:3048', 'nullable'],
        ];
    }

    public function messages()
    {
        return [
        'image' => '指定されたファイルが画像ではありません。',
        'mines' => '指定された拡張子（jpg/jpeg/png）ではありません。',
        'max' => 'ファイルサイズは3MB以内にしてください。',
    ];
    }
}
