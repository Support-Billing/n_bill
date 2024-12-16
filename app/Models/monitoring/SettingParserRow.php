<?php

namespace App\Models\monitoring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingParserRow extends Model
{
    protected $connection = 'mysql_third';
    protected $table = "setting_parser_row";
    protected $primaryKey = "idx_parser";
    public $incrementing = false;
    public $timestamps = true;
    
    // protected $fillable = [
    //     'FolderName',
    //     // tambahkan atribut lainnya yang diizinkan untuk mass assignment di sini
    // ];
}
