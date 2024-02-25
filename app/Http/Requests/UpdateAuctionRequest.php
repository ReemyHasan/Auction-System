<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateAuctionRequest extends FormRequest
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
            "product_id"=> "",
            "lowest_price"=> "regex:/^[0-9]+(\.[0-9][0-9]?)?$/",
            "closing_price"=> "gt:lowest_price|regex:/^[0-9]+(\.[0-9][0-9]?)?$/",
            "start_time"=> "date|before_or_equal:closing_time",
            "closing_time"=> "date|after_or_equal:start_time",
        ];
    }
    public function failedValidation(Validator $validator)
    {
        return redirect()->back()->withErrors($validator);
    }
}
