<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (Auth::user()->type==1 || Auth::user()->type==3);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "max:25|min:5",
            "description" => "max:255|min:5",
            "status" => "lt:2",
            "count"=> "integer",
            "image"=> "image",
        ];
    }
    public function failedValidation(Validator $validator)
    {
        return redirect()->back()->withErrors($validator);
    }
}
