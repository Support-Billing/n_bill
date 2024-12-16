<?php

namespace App\Models\sum;

use Illuminate\Database\Eloquent\Model;


class ReportCdr extends Model
{

    protected $connection = 'mysql_second';
    
    protected $table = "reportCdr";
    // protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    
    protected $primaryKey = ['date', 'idxCoreProject'];
}
