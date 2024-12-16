<?php

namespace App\Models\sum;

use Illuminate\Database\Eloquent\Model;



class ReportUsageCustomer extends Model
{

    protected $connection = 'mysql_second';
    
    protected $table = "reportUsageCustomer";
    public $incrementing = false;
    public $timestamps = true;
    
    protected $primaryKey = ['date', 'idxCoreProject', 'idxCorePrefix'];
    
}
