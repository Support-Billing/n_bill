<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Superadmin
        $role = Role::where("name", "Superadmin")->firstOrFail()->id;
        $employee = Employee::where("name", "Ferosa Superadmin")->firstOrFail()->id;
        $administrator = new User;
        $administrator->id = str::Uuid(36);
        $administrator->id_employee = $employee;
        $administrator->id_role = $role;
        $administrator->username = "Superadmin";
        $administrator->email = "Superadmin@quiros.co.id";
        $administrator->password = Hash::make("Superadmin");
        $administrator->save();


        $role = Role::where("name", "Sales")->firstOrFail()->id;
        $employee = Employee::where("name", "Martin Sales")->firstOrFail()->id;
        User::create([
            'id' => str::Uuid(36),
            'id_employee' => $employee,
            'id_role' => $role,
            'username' => "Sales",
            'email' => "Sales@quiros.co.id",
            'password' => Hash::make("Sales")
        ]);
        $role = Role::where("name", "Sales")->firstOrFail()->id;
        $employee = Employee::where("name", "Della Sales")->firstOrFail()->id;
        User::create([
            'id' => str::Uuid(36),
            'id_employee' => $employee,
            'id_role' => $role,
            'username' => "DellaSales",
            'email' => "Della.Sales@quiros.co.id",
            'password' => Hash::make("Sales")
        ]);

        $role = Role::where("name", "Sales Admin")->firstOrFail()->id;
        $employee = Employee::where("name", "Panca Sales Admin")->firstOrFail()->id;
        User::create([
            'id' => str::Uuid(36),
            'id_employee' => $employee,
            'id_role' => $role,
            'username' => "Sales Admin",
            'email' => "SalesAdmin@quiros.co.id",
            'password' => Hash::make("SalesAdmin")
        ]);

        $role = Role::where("name", "Admin")->firstOrFail()->id;
        $employee = Employee::where("name", "Eka Admin")->firstOrFail()->id;
        User::create([
            'id' => str::Uuid(36),
            'id_employee' => $employee,
            'id_role' => $role,
            'username' => "Admin",
            'email' => "Admin@quiros.co.id",
            'password' => Hash::make("Admin")
        ]);

        $role = Role::where("name", "Engineer")->firstOrFail()->id;
        $employee = Employee::where("name", "Ritchi Engineer")->firstOrFail()->id;
        User::create([
            'id' => str::Uuid(36),
            'id_employee' => $employee,
            'id_role' => $role,
            'username' => "Engineer",
            'email' => "Engineer@quiros.co.id",
            'password' => Hash::make("Engineer")
        ]);

        $role = Role::where("name", "BOD")->firstOrFail()->id;
        $employee = Employee::where("name", "Tegar BOD")->firstOrFail()->id;
        User::create([
            'id' => str::Uuid(36),
            'id_employee' => $employee,
            'id_role' => $role,
            'username' => "BOD",
            'email' => "BOD@quiros.co.id",
            'password' => Hash::make("BOD")
        ]);

    }
}
