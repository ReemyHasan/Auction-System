<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Product extends BaseModel
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
    protected $with = [
        "category",
        "user"
    ];
    public function category(){
        return $this->belongsTo(Category::class,"category_id");
    }
    public function user(){
        return $this->belongsTo(User::class,"vendor_id");
    }
    public function get_imageUrl()
    {
        if ($this->image) {
            return url('storage/products/' . $this->image);
        }
    }
    public function scopeFilter($query){
        if(request()->has("name")){
            $query->where("name","like","%".request()->get("name")."%");
        }
        if(request()->has("created_at")){
            $query->where("created_at","like","%".request()->get("created_at")."%");
        }
        if(request()->has("category_id")){
            $query->where("category_id","like","%".request()->get("category_id")."%");
        }
        if(request()->has("my_products")){
            $query->where('vendor_id','=',Auth::user()->id);
        }
    }
    public function interactions(): MorphMany
    {
        return $this->morphMany(Interaction::class, 'interactionable');
    }
}
