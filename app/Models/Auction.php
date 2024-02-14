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
    public function product(){
        return $this->belongsTo(Product::class,"product_id");
    }
    public static function getRecords(){
        return self::orderBy("created_at","desc");
    }
    public static function getRecord($id){
        return self::where("id",$id)->first();
    }
    public function scopeFilter($query){
        if(request()->has("name")){
            $query->where("name","like","%".request()->get("name")."%");
        }
        if(request()->has("created_at")){
            $query->where("created_at","like","%".request()->get("created_at")."%");
        }
    }
}
