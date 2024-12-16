<?php

namespace App\Models\sum;

use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;


class ElapsedTime extends Model
{

    protected $connection = 'mysql_second';
    
    protected $table = "elapsedtime";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    
}
