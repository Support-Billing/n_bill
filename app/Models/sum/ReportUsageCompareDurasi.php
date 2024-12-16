<?php

namespace App\Models\sum;

use Illuminate\Database\Eloquent\Model;



class ReportUsageCompareDurasi extends Model
{

    protected $connection = 'mysql_second';
    
    protected $table = "reportUsageCompareDurasi";
    public $incrementing = false;
    public $timestamps = true;
    
    // protected $primaryKey = ['date', 'idxCoreProject', 'idxCorePrefix'];
    
}
