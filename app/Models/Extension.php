<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Extension extends Model
{
    
    protected $table = "extensions";
    protected $primaryKey = "extensionID";
    
    public $incrementing = true;
    public $timestamps = true;
}
