<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithUpserts;
use App\Models\setting_parser;

class ExampleImport implements ToCollection
{
    
    protected $idx;

    public function __construct($idx)
    {
        $this->idx = $idx;
    }
    
    public function collection(Collection $rows)
    {
        // Proses setiap baris dari file Excel
        foreach ($rows as $row) {
            print_r($row);exit;

            // Contoh upsert ke dalam database berdasarkan kolom tertentu
            // $success = setting_parser::updateOrInsert(
            //     ['idx' => $this->idx], // Kolom kunci untuk mencocokkan data
            //     ['DataXample' => $row] // Data yang akan diupdate atau diinsert
            // );
            $success = setting_parser::updateOrInsert(
                ['idx' => $this->idx], // Kolom kunci untuk mencocokkan data
                ['DataXample' => $row] // Data yang akan diupdate atau diinsert
            );
            // break; // Hentikan loop
        }

        return $success;
    }

    public function uniqueBy()
    {
        // Tentukan kolom kunci unik untuk operasi upsert
        return 'idx';
    }
}
