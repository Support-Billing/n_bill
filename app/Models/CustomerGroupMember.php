<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Customer;
use App\Models\Project;

class CustomerGroupMember extends Model
{

    use SoftDeletes;
    protected $table = "customer_group_members";
    protected $primaryKey = "idxCustomerGroup";
    public $incrementing = true;
    public $timestamps = true;
    
    public function customer(): belongsTo
    {
        return $this->belongsTo(Customer::class, 'idxCustomer');
    }

    public function project(): belongsTo
    {
        return $this->belongsTo(Project::class, 'idxProjectDesktop');
    }
    
}
