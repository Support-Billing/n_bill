<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Server extends Model
{
    
    use SoftDeletes;
    protected $table = "servers";
    protected $primaryKey = "serverID";
    public $incrementing = false;
    public $timestamps = true;
}
