<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectPrefixIp extends Model
{
    protected $table = "project_prefix_ip";
    protected $primaryKey = "idx";
    public $incrementing = false;
    public $timestamps = true;
}
