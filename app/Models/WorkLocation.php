<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Relations\HasMany;


class WorkLocation extends Model
{
    
    protected $table = "worklocations";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    
    public function employee(): HasMany
    {
        return $this->hasMany(Employee::class, "id_worklocation", "id");
    }

}
