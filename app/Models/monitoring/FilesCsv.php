<?php

namespace App\Models\monitoring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilesCsv extends Model
{
    protected $connection = 'mysql_third';
    protected $table = "files_csv";
    protected $primaryKey = "idx";
    public $incrementing = false;
    public $timestamps = true;
}
