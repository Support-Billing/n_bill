<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Project;

class Cdr extends Model
{

    protected $table = "cdr";
    public $timestamps = true;
    protected $fillable = ['dataServer'];
    
    public function Project() : BelongsTo
    {
        return $this->belongsTo(Project::class, 'idxCustomer', 'idxDesktop');
    }

}
