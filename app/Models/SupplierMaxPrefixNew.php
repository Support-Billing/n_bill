<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierMaxPrefixNew extends Model
{
    use HasFactory;
    protected $table = "supplier_maxprefix_news";
    // protected $primaryKey = "idx";
    // public $incrementing = true;
    public $timestamps = true;
}
