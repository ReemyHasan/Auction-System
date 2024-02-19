<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerBid extends BaseModel
{
    use HasFactory, SoftDeletes;
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
    public static function getCustomerBidsForAuction($user,$auction){
        return self::where("customer_id","=",$user->id)->where("auction_id","=",$auction->id)->orderBy("created_at","desc");
    }
    public function scopeFilter($query){
        if(!empty(request("created_at"))){
            $query->where("created_at","like","%".request()->get("created_at")."%");
        }
    }
}
