<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerBid extends Model
{
    use HasFactory;
    protected $table = "customer_bid";
    protected $fillable = [
        "customer_id",
        "auction_id",
        "price"
    ] ;
    public function customer(){
        return $this->belongsTo(User::class,"customer_id");
    }
    public function auction(){
        return $this->belongsTo(Auction::class,"auction_id");
    }
}
