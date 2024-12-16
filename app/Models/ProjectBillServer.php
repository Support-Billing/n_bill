<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectBillServer extends Model
{
    use SoftDeletes;
    protected $table = "project_billserver";
    protected $primaryKey = "billserverID";
    public $incrementing = true;
    public $timestamps = true;
}
