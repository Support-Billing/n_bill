<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SupplierIpPrefix extends Model
{
    use SoftDeletes;
    protected $table = "supplier_ip_prefixes";
    protected $primaryKey = "idx";
    public $incrementing = true;
    public $timestamps = true;
}
