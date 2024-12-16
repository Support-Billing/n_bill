<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\WorkLocation;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    
    use SoftDeletes;
    protected $table = "employees";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    
    public function worklocation(): BelongsTo
    {
        return $this->BelongsTo(WorkLocation::class, 'id_worklocation', 'id');
    }

    public function user(): HasMany
    {
        return $this->hasMany(User::class, "id_employee", "id");
    }

}
