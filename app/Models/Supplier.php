<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    
    use SoftDeletes;
    protected $table = "suppliers";
    protected $primaryKey = "idx";
    public $incrementing = true;
    public $timestamps = true;
}
