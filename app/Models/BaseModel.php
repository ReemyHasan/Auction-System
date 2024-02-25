<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public static function getRecords()
    {
        // return self::orderBy("start_time", "desc");
        return self::orderBy("created_at", "desc");

    }
    public static function getRecord($id)
    {
        return self::where("id", $id)->first();
    }
}
