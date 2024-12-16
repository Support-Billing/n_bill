<?php

namespace App\Models\monitoring;

use Illuminate\Database\Eloquent\Model;

class RepairCsv extends Model
{
    protected $connection = 'mysql_third';
    protected $table = "repair_csv";
    public $timestamps = true;
    protected $fillable = ['dataServer'];
}
