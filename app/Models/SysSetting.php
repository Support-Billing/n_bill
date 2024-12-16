<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysSetting extends Model
{
    
    protected $table = "syssettings";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
}
