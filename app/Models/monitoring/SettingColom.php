<?php

namespace App\Models\monitoring;

use Illuminate\Database\Eloquent\Model;


class SettingColom extends Model
{

    protected $connection = 'mysql_third';
    protected $table = "setting_colom";
    protected $primaryKey = "idx";
    public $incrementing = false;
    public $timestamps = true;
    
}
