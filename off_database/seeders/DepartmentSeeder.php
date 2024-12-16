<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use Illuminate\Support\str;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::create([
            'id' => str::Uuid(36),
            'name' => "BSO"
        ]);
        Department::create([
            'id' => str::Uuid(36),
            'name' => "Verificator Head"
        ]);
        Department::create([
            'id' => str::Uuid(36),
            'name' => "Disburse"
        ]);
        Department::create([
            'id' => str::Uuid(36),
            'name' => "Legal"
        ]);
        Department::create([
            'id' => str::Uuid(36),
            'name' => "CPR Head"
        ]);
    }
}
