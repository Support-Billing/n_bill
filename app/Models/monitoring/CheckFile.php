<?php

namespace App\Models\monitoring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckFile extends Model
{
    protected $connection = 'mysql_third';
    protected $table = "check_file";
    protected $primaryKey = "idx";
    public $incrementing = false;
    public $timestamps = true;
}
