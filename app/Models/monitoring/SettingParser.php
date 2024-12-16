<?php

namespace App\Models\monitoring;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\monitoring\SettingParserRegex;
use App\Models\monitoring\SettingParserRow;

class SettingParser extends Model
{
    protected $connection = 'mysql_third';
    protected $table = "setting_parser";
    protected $primaryKey = "idx";
    public $incrementing = false;
    public $timestamps = true;
    
    protected $fillable = [
        'FolderName',
        // tambahkan atribut lainnya yang diizinkan untuk mass assignment di sini
    ];

    public function setting_parser_regex(): HasOne
    {
        return $this->HasOne(SettingParserRegex::class, "idx_parser", "idx");
    }

    public function setting_parser_row(): HasOne
    {
        return $this->HasOne(SettingParserRow::class, "idx_parser", "idx");
    }
}
