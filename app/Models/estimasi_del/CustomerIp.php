<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerIp extends Model
{
    // use HasFactory;
    protected $table = "customer_ips";
    protected $primaryKey = "idx";
    public $incrementing = true;
    public $timestamps = true;
}
