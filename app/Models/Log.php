<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 
class Log extends Model
{
    protected $table = "logs";
    protected $primaryKey = "idx";
    public $incrementing = true;
    public $timestamps = true;
}
