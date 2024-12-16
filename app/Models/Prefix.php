<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prefix extends Model
{
    
    protected $table = "prefixes";
    protected $primaryKey = "id";
    public $incrementing = false;
    public $timestamps = true;
}
