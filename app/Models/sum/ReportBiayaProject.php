<?php

namespace App\Models\sum;

use Illuminate\Database\Eloquent\Model;



class ReportBiayaProject extends Model
{

    protected $connection = 'mysql_second';
    
    protected $table = "ReportBiayaProject";
    public $incrementing = false;
    public $timestamps = true;
    
    protected $primaryKey = ['date', 'idxCoreProject', 'idxCorePrefix'];
    
}
