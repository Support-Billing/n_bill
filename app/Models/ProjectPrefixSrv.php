<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectPrefixSrv extends Model
{
    use SoftDeletes;
    protected $table = "project_prefix_srv";
    protected $primaryKey = "idxCore";
    public $incrementing = false;
    public $timestamps = true;
}
