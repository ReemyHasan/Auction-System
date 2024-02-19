<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends BaseModel
{
    use HasFactory;
    protected $guarded = [
        "id",
        "created_at",
        "updated_at",
    ];
    public function user(){
        return $this->belongsTo(User::class,'created_by');
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
