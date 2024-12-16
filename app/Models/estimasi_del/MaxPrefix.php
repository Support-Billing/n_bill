<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaxPrefix extends Model
{
    use HasFactory;
    protected $table = "maxprefixes";
    // protected $primaryKey = "bankID";
    // public $incrementing = false;
    public $timestamps = true;
}
