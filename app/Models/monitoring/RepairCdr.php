<?php

namespace App\Models\monitoring;

use Illuminate\Database\Eloquent\Model;

class RepairCdr extends Model
{
    
    // protected $connection = 'mysql_third';
    protected $table = "repaircdr";
    protected $primaryKey = "idx";
    public $incrementing = false;
    public $timestamps = true;

}
