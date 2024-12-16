<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerGroupPrice extends Model
{
    
    use SoftDeletes;
    protected $table = "customer_group_prices";
    protected $primaryKey = "idxCore";
    public $incrementing = false;
    public $timestamps = true;
}
