<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Project;

class NoCdr extends Model
{

    protected $table = "nocdr";
    public $timestamps = true;
    protected $fillable = ['dataServer'];
    
    public function Project() : BelongsTo
    {
        return $this->belongsTo(Project::class, 'idxCustomer', 'idxDesktop');
    }

}
