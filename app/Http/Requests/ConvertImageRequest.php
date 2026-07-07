<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConvertImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'document' => ['required', 'file', 'mimes:jpeg,jpg,png,webp,bmp', 'max:20480'],
            'target_format' => ['required', 'string', 'in:png,jpg,webp,bmp'],
        ];
    }

    public function messages(): array
    {
        return [
            'document.required' => 'Please select an image file to convert.',
            'document.file' => 'The uploaded file is invalid.',
            'document.mimes' => 'Only JPG, JPEG, PNG, WEBP, and BMP files are supported.',
            'document.max' => 'The file must not exceed 20 MB.',
        ];
    }
}
