<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectPrefixAccount extends Model
{
    use SoftDeletes;
    protected $table = "project_prefix_accounts";
    protected $primaryKey = "prefixAccID";
    public $incrementing = true;
    public $timestamps = true;
}
