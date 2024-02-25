<?php

namespace App\Rules;

use App\Models\Auction;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class GTLowestPrice implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $auction = Auction::getRecord(request('auction_id'));
        $bids = $auction->bids()->where('price','>', $value)->get();
        if ($bids->count() > 0 || $auction->lowest_price > $value) {
            $fail('should be greater than all previous bids and lowest price');
        }
    }
}
