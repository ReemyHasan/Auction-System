<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "name",
        "description",
        "status",
        "image",
        "count",
        "vendor_id",
        "category_id",
    ];
    public function category(){
        return $this->belongsTo(Category::class,"category_id");
    }
    public function user(){
        return $this->belongsTo(User::class,"vendor_id");
    }
    public static function getRecords(){
        return self::orderBy("created_at","desc")->get();
    }
    public static function getRecord($id){
        return self::where("id",$id)->first();
    }
    public function get_imageUrl()
    {
        if ($this->image) {
            return url('storage/' . $this->image);
        }
    }
}
