<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerIpPrefix extends Model
{
    
    protected $table = "customer_ip_prefix";
    protected $primaryKey = "idx";
    public $incrementing = true;
    public $timestamps = true;
}
