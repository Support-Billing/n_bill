<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerIp extends Model
{
    
    protected $table = "customer_ip";
    protected $primaryKey = "idx";
    public $incrementing = true;
    public $timestamps = true;
}
