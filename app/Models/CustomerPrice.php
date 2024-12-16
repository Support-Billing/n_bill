<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerPrice extends Model
{
    
    protected $table = "customer_prices";
    protected $primaryKey = "idx";
    public $incrementing = true;
    public $timestamps = true;
    
}
