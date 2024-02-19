<?php

namespace App\Http\Requests;

use App\Models\Auction;
use App\Rules\GTLowestPrice;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BidRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (Auth::user()->type==2 || Auth::user()->type==3);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "auction_id"=> "required",
            "price"=> ["required","regex:/^[0-9]+(\.[0-9][0-9]?)?$/", new GTLowestPrice],
        ];
    }
    public function failedValidation(Validator $validator)
    {
        return redirect()->back()->withErrors($validator);
    }
}
