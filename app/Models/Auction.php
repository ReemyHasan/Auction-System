<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    public static function getRecords(){
        return self::orderBy("start_time","desc");
    }
    public static function getRecord($id){
        return self::where("id",$id)->first();
    }
    public function scopeFilter($query){
        if(request()->has("name") && !empty(request('name'))){
            $product = Product::where("name","like","%".request()->get("name")."%")->first();
            $query->where("product_id","like","%".$product->id."%");
        }
        if(request()->has("category_id")){
            $query->join("products","products.id","=","product_id")
            ->where("category_id","like","%".request()->get("category_id")."%");
            // where("name","like","%".request()->get("name")."%");
        }
        if(request()->has("start_time")){
            $query->where("start_time","like","%".request()->get("start_time")."%");
        }
    }
}
