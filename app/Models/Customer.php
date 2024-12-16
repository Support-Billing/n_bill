<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Project;
use App\Models\CustomerGroupMember;

class Customer extends Model
{
    
    use SoftDeletes;
    protected $table = "customers";
    protected $primaryKey = "idxCore";
    public $incrementing = false;
    public $timestamps = true;

    /**
     * Get the customer for the project data.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'idxCoreCust', 'idxCore');
    }

    public function groups()
    {
        return $this->hasMany(CustomerGroupMember::class, 'idxCustomer');
    }

    /**
     * Get the customer for the project data.
     */
    public function scopeNotInGroup($query)
    {
        return $query->whereNotIn('idxCore', function ($subquery) {
            $subquery->select('idxProjectDesktop')->from('customer_group_members');
        });
    }

}
