<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPriceBackup extends Model
{
    protected $table = "customer_price_backups";
    protected $primaryKey = "idx";
    public $incrementing = true;
    public $timestamps = true;
}
