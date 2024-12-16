<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Worklocation;
use Illuminate\Support\str;

class WorklocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Worklocation::create([
            'id' => str::Uuid(36),
            'name' => "PT. Telmark Integrasi Indonesia",
            'phone' => "622150300050",
            'address' => "Rukan Citta Graha, Jl. Panjang No 26 Blok 2c Kedoya Selatan, Kebon Jeruk, Jakarta Barat 11520"
        ]);
        Worklocation::create([
            'id' => str::Uuid(36),
            'name' => "PT Quiros Networks",
            'phone' => "622140882020",
            'address' => "Business Park Kebon Jeruk Blok D2/7 Jl. Raya Meruya Ilir No. 88, Jakarta Barat 11620, Indonesia"
        ]);
    }
}
