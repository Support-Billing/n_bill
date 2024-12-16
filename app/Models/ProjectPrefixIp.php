<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectPrefixIp extends Model
{
    use SoftDeletes;
    protected $table = "project_prefix_ip";
    protected $primaryKey = "idxCore";
    public $incrementing = true;
    public $timestamps = true;
}
