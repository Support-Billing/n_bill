<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierIp extends Model
{
    // use HasFactory;
    protected $table = "supplier_ips";
    protected $primaryKey = "idx";
    public $incrementing = true;
    public $timestamps = true;
}
