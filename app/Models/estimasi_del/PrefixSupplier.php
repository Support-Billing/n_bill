<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrefixSupplier extends Model
{
    
    protected $table = "prefix_suppliers";
    protected $primaryKey = "projectID";
    public $incrementing = false;
    public $timestamps = true;
}
