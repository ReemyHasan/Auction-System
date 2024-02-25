<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Interaction extends Model
{
    use HasFactory;
    protected $guarded = [
        "id",
        "created_at",
        "updated_at",
    ] ;
    public function interactionable(): MorphTo
    {
        return $this->morphTo();
    }
}
