<?php

namespace App\Models\sum;

use Illuminate\Database\Eloquent\Model;



class ReportBiayaCustomer extends Model
{

    protected $connection = 'mysql_second';
    
    protected $table = "reportBiayaCustomer";
    public $incrementing = false;
    public $timestamps = true;
    
    protected $primaryKey = ['date', 'idxCoreProject', 'idxCorePrefix'];
    
}
