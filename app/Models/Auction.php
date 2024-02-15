<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Auction extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [
        "id",
        "created_at",
        "updated_at",
    ];
    protected $with = [
        "product"
    ];
    public function product(){
        return $this->belongsTo(Product::class,"product_id");
    }
    public function bids(){
        return $this->hasMany(CustomerBid::class,"auction_id");
    }
    public static function getRecords(){
        return self::orderBy("start_time","desc");
    }
    public static function getRecord($id){
        return self::where("id",$id)->first();
    }
    public function scopeFilter($query){
        if(!empty(request('name'))){
            $product = Product::where("name","like","%".request()->get("name")."%")->first();
            $query->where("product_id","like","%".$product->id."%");
        }
        if(!empty(request("category_id"))){
            $query->join("products","products.id","=","product_id")
            ->where("category_id","like","%".request()->get("category_id")."%");
        }
        if(!empty(request("start_time"))){
            $query->where("start_time","like","%".request()->get("start_time")."%");
        }
        if(request()->has("my_auctions")){
            $query->join("products","products.id","=","product_id")
            ->where('vendor_id','=',Auth::user()->id);
        }
    }
}
