<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierMaxPrefix extends Model
{
    use HasFactory;
    protected $table = "supplier_maxprefixes";
    public $timestamps = true;
}
