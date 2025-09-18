<?php

namespace App\Http\Requests\Api\SS;

use Illuminate\Foundation\Http\FormRequest;

class Upload extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
