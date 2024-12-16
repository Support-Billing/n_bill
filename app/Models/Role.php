<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    
    protected $table = "roles";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;

    public function user(): HasMany
    {
        return $this->hasMany(User::class, "id_role", "id");
    }
}
