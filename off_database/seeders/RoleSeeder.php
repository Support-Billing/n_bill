<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\modul;
use Illuminate\Support\str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $idSuperadmin = str::Uuid(36);
        role::create([
            'id' => $idSuperadmin,
            'name' => "Superadmin",
            'description' => "-"
        ]);

        $idSales = str::Uuid(36);
        role::create([
            'id' => $idSales,
            'name' => "Sales",
            'description' => "-"
        ]);

        $idSalesAdmin = str::Uuid(36);
        role::create([
            'id' => $idSalesAdmin,
            'name' => "Sales Admin",
            'description' => "-"
        ]);

        $idAdmin = str::Uuid(36);
        role::create([
            'id' => $idAdmin,
            'name' => "Admin",
            'description' => "-"
        ]);

        $idEngineer = str::Uuid(36);
        role::create([
            'id' => $idEngineer,
            'name' => "Engineer",
            'description' => "-"
        ]);

        $idBOD = str::Uuid(36);
        role::create([
            'id' => $idBOD,
            'name' => "BOD",
            'description' => "-"
        ]);
    }
}
