<?php

namespace App\Models\sum;

use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;


class ReportInvoice extends Model
{

    protected $connection = 'mysql_second';
    
    protected $table = "reportInvoice";
    public $incrementing = false;
    public $timestamps = true;
    
    protected $primaryKey = ['date', 'idxCoreProject'];
    
}
