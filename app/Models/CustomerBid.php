<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerBid extends Model
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

    public static function getRecords(){
        return self::orderBy("created_at","desc");
    }
    public static function getRecord($id){
        return self::where("id",$id)->first();
    }
    public static function getCustomerBidsForAuction($user,$auction){
        return self::where("customer_id","=",$user->id)->where("auction_id","=",$auction->id);
    }
    public function scopeFilter($query){
        if(!empty(request("created_at"))){
            $query->where("created_at","like","%".request()->get("created_at")."%");
        }
    }
}
