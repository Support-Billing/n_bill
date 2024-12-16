<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CustomerGroupPrice;
use App\Models\Project;
use App\Models\Customer;
// use App\Models\CustomerGroupMember;


class CustomerGroup extends Model
{
    use SoftDeletes;
    protected $table = "customer_groups";
    protected $primaryKey = "idxCore";
    public $incrementing = false;
    public $timestamps = true;

    public function prices(): HasMany
    {
        // key dari idxCoreCustomerGroup table CustomerGroupPrice
        // key dari 'idxCore' table CustomerGroupPrice
        return $this->hasMany(CustomerGroupPrice::class, 'idxCoreCustGroup', 'idxCore');
    }

    public function projects(): HasMany
    {
        // key dari idxCoreCustomerGroup table CustomerGroupPrice
        // key dari 'idxCore' table CustomerGroupPrice
        return $this->hasMany(Project::class, 'idxCoreCustGroup', 'idxCore');
    }

    public function customers(): HasMany
    {
        // key dari idxCoreCustomerGroup table CustomerGroupPrice
        // key dari 'idxCore' table CustomerGroupPrice
        return $this->hasMany(Customer::class, 'idxCoreCustGroup', 'idxCore');
    }

    // public function members(): HasMany
    // {
    //     return $this->hasMany(CustomerGroupMember::class, 'idxCustomerGroupDesktop');
    // }
    
    // public function customer()
    // {
    //     return $this->hasMany(customer::class, 'customer_group_id');
    // }

    // public static function onlyquery()
    // {
    //     $query = " SELECT b.idxCustomerGroup,b.idxCustomer,c.clientName FROM `customer_groups` a 
    // LEFT JOIN `customer_group_members` b ON a.idx = b.idxCustomerGroup
    // LEFT JOIN `customers` c ON b.idxCustomer = c.idx WHERE b.idxCustomerGroup = 2";
        
    //     $data  = DB::select($query);

    //     return $data;
    // }
}
