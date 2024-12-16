<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithUpserts;
use App\Models\setting_parser;

class ExampleImport implements ToModel, WithUpserts
{

    protected $idx;

    public function __construct($idx)
    {
        $this->idx = $idx;
    }
    
    public function model(array $rows)
    {
    
        foreach ($rows as $row) {
	
            $success = new setting_parser([
                    'FolderName' => $rows[2],
                    'XampleFile' => $rows[3]
                ]);
        }

        return $success;
    }

    public function uniqueBy()
    {
        // Tentukan kolom kunci unik untuk operasi upsert
        return 'idx';
    }
}
