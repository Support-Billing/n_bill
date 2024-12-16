<?php

namespace App\Models\monitoring;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    
    protected $connection = 'mysql_third';
    protected $table = "files";
    protected $primaryKey = "idx";
    public $incrementing = true;
    public $timestamps = true;

}
