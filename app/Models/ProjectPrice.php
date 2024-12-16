<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectPrice extends Model
{
    use SoftDeletes;
    protected $table = "project_prices";
    protected $primaryKey = "priceID";
    public $incrementing = true;
    public $timestamps = true;
}
