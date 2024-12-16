<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\ProjectPrice;
use App\Models\ProjectPrefixSrv;
use App\Models\ProjectPrefixIp;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use SoftDeletes;
    protected $table = "projects";
    protected $primaryKey = "idxCore";
    public $incrementing = false;
    public $timestamps = true;
    
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'idxCoreCust', 'idxCore');
    }
    
    public function ProjectPrice(): HasMany
    {
        return $this->hasMany(ProjectPrice::class, 'idxProject');
    }

    public function ProjectPrefixSrvs(): HasMany
    {
        return $this->hasMany(ProjectPrefixSrv::class, 'idxCoreProject', 'idxCore');
    }

    public function ProjectPrefixIps(): HasMany
    {
        return $this->hasMany(ProjectPrefixIp::class, 'idxCoreProject', 'idxCore');
    }
    
    public function cdrs(): HasMany
    {
        return $this->hasMany(Cdr::class, 'client_id');
    }
    
}
