<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\bank;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        bank::create([
            'bankName' => 'BCA (IDR)',
            'bankAcc' => '656.003.3433',
            'bankCode' => "NON CLI",
            'bankAddress' => "KCP Business Park",
            'accName' => 'PT. Quiros Networks',
            'statusData' => 1
        ]);
        bank::create([
            'bankName' => 'BCA',
            'bankAcc' => '372.309.9290',
            'bankCode' => "CLI",
            'bankAddress' => "KCP Business Park",
            'accName' => 'PT. Quiros Networks',
            'statusData' => 1
        ]);
        bank::create([
            'bankName' => 'Bank Sinarmas',
            'bankAcc' => '656.003.3433',
            'bankCode' => "NON CLI",
            'bankAddress' => "Puri Kembangan",
            'accName' => 'PT. Quiros Networks',
            'statusData' => 1
        ]);
    }
}
