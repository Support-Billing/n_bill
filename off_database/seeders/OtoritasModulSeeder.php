<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\modul;
use App\Models\otoritas_modul;

class OtoritasModulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modul = modul::orderBy('list_number', 'ASC')->get();
        $Role = Role::orderBy('id', 'ASC')->get();
        foreach ($Role as $value_role) {
	        foreach ($modul as $value) {
	        	if ($value_role->name == 'Superadmin') {
			        otoritas_modul::create([
						'id_menu' => $value->id,
						'id_role' => $value_role->id,
						'view_otoritas_modul' => 1,
						'insert_otoritas_modul' => 1,
						'update_otoritas_modul' => 1,
						'delete_otoritas_modul' => 1,
						'export_otoritas_modul' => 1,
						'import_otoritas_modul' => 1,
						'data_otoritas_modul' => 1
			        ]);
	        	}else{

		        	if ($value->name == 'Dashboard') {
				        otoritas_modul::create([
							'id_menu' => $value->id,
							'id_role' => $value_role->id,
							'view_otoritas_modul' => 1,
							'insert_otoritas_modul' => 0,
							'update_otoritas_modul' => 0,
							'delete_otoritas_modul' => 0,
							'export_otoritas_modul' => 0,
							'import_otoritas_modul' => 0,
							'data_otoritas_modul' => 1
				        ]);
		        	}

		        	if ($value->name == 'Settings') {
				        otoritas_modul::create([
							'id_menu' => $value->id,
							'id_role' => $value_role->id,
							'view_otoritas_modul' => 1,
							'insert_otoritas_modul' => 0,
							'update_otoritas_modul' => 0,
							'delete_otoritas_modul' => 0,
							'export_otoritas_modul' => 0,
							'import_otoritas_modul' => 0,
							'data_otoritas_modul' => 1
				        ]);
		        	}

		        	if ($value->name == 'Profile') {
				        otoritas_modul::create([
							'id_menu' => $value->id,
							'id_role' => $value_role->id,
							'view_otoritas_modul' => 1,
							'insert_otoritas_modul' => 0,
							'update_otoritas_modul' => 0,
							'delete_otoritas_modul' => 0,
							'export_otoritas_modul' => 0,
							'import_otoritas_modul' => 0,
							'data_otoritas_modul' => 1
				        ]);
		        	}
		        	if ($value->name != 'Dashboard' && $value->name != 'Settings' && $value->name != 'Profile') {
				        otoritas_modul::create([
							'id_menu' => $value->id,
							'id_role' => $value_role->id,
							'view_otoritas_modul' => 0,
							'insert_otoritas_modul' => 0,
							'update_otoritas_modul' => 0,
							'delete_otoritas_modul' => 0,
							'export_otoritas_modul' => 0,
							'import_otoritas_modul' => 0,
							'data_otoritas_modul' => 1
				        ]);
		        	}
	        	}
			}
		}
    }
}
