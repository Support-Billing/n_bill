<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrefixGroup extends Model
{
    protected $table = "prefix_groups";
    protected $primaryKey = "idx";
    public $incrementing = true;
    public $timestamps = true;
}
