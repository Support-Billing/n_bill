<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierPrice extends Model
{
    use HasFactory;
    protected $table = "supplier_prices";
    protected $primaryKey = "idx";
    public $incrementing = true;
    public $timestamps = true;
}
