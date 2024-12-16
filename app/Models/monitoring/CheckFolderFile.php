<?php

namespace App\Models\monitoring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckFolderFile extends Model
{
    protected $connection = 'mysql_third';
    protected $table = "check_folder_file";
    protected $primaryKey = "idx";
    public $incrementing = false;
    public $timestamps = true;
}
