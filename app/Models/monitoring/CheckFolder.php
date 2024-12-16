<?php

namespace App\Models\monitoring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckFolder extends Model
{
    protected $connection = 'mysql_third';
    protected $table = "check_folder";
    protected $primaryKey = "idx";
    public $incrementing = false;
    public $timestamps = true;
}
