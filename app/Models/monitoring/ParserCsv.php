<?php

namespace App\Models\monitoring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParserCsv extends Model
{
    protected $connection = 'mysql_third';
    protected $table = "parser_csv";
    protected $primaryKey = "idxCore";
    public $incrementing = false;
    public $timestamps = true;
}
