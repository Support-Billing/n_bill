<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Worklocation;
use Illuminate\Support\str;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $worklocation = Worklocation::first();
        $id_worklocation = $worklocation->id;
        Employee::create([
            'id' => str::Uuid(36),
            'id_worklocation' => $id_worklocation,
            'name' => "Ferosa Superadmin",
            'nik' => "0897657633111",
            'phone' => "0897657633",
            'city' => "Bandung",
            'status' => "ACTIVE",
            'avatar' => "saat-ini-tidak-ada-file.png",
            'address' => "Sarmili, Bintaro, Tangerang Selatan"
        ]);

        Employee::create([
            'id' => str::Uuid(36),
            'id_worklocation' => $id_worklocation,
            'name' => "Martin Sales",
            'nik' => "0897657633222",
            'phone' => "0897657633",
            'city' => "Bandung",
            'status' => "ACTIVE",
            'avatar' => "saat-ini-tidak-ada-file.png",
            'address' => "Sarmili, Bintaro, Tangerang Selatan"
        ]);

        Employee::create([
            'id' => str::Uuid(36),
            'id_worklocation' => $id_worklocation,
            'name' => "Panca Sales Admin",
            'nik' => "0897657633333",
            'phone' => "0897657633",
            'city' => "Bandung",
            'status' => "ACTIVE",
            'avatar' => "saat-ini-tidak-ada-file.png",
            'address' => "Sarmili, Bintaro, Tangerang Selatan"
        ]);

        Employee::create([
            'id' => str::Uuid(36),
            'id_worklocation' => $id_worklocation,
            'name' => "Eka Admin",
            'nik' => "0897657633444",
            'phone' => "0897657633",
            'city' => "Bandung",
            'status' => "ACTIVE",
            'avatar' => "saat-ini-tidak-ada-file.png",
            'address' => "Sarmili, Bintaro, Tangerang Selatan"
        ]);

        Employee::create([
            'id' => str::Uuid(36),
            'id_worklocation' => $id_worklocation,
            'name' => "Ritchi Engineer",
            'nik' => "0897657633555",
            'phone' => "0897657633",
            'city' => "Bandung",
            'status' => "ACTIVE",
            'avatar' => "saat-ini-tidak-ada-file.png",
            'address' => "Sarmili, Bintaro, Tangerang Selatan"
        ]);

        Employee::create([
            'id' => str::Uuid(36),
            'id_worklocation' => $id_worklocation,
            'name' => "Tegar BOD",
            'nik' => "0897657633666",
            'phone' => "0897657633",
            'city' => "Bandung",
            'status' => "ACTIVE",
            'avatar' => "saat-ini-tidak-ada-file.png",
            'address' => "Sarmili, Bintaro, Tangerang Selatan"
        ]);

        Employee::create([
            'id' => str::Uuid(36),
            'id_worklocation' => $id_worklocation,
            'name' => "Della Sales",
            'nik' => "0897657633777",
            'phone' => "0897657633",
            'city' => "Bandung",
            'status' => "ACTIVE",
            'avatar' => "saat-ini-tidak-ada-file.png",
            'address' => "Sarmili, Bintaro, Tangerang Selatan"
        ]);


    }
}
