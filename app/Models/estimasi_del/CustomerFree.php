<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerFree extends Model
{
    // use HasFactory;
    protected $table = "customer_frees";
    protected $primaryKey = "idx";
    public $incrementing = true;
    public $timestamps = true;
}
