<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\modul;
use Illuminate\Support\str;

class ModulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Dashboard
        modul::create([
            'id' => str::Uuid(36),
			'url' => 'dashboard',
			'name' => 'Dashboard',
			'description' => 'first show data',
			'type' => '0',
			'list_number' => 1,
			'icon' => 'fa-home',
        ]);


		// $Customer_Uuid = str::Uuid(36);
		modul::create([
			'id' => str::Uuid(36),
			'url' => 'bank',
			'name' => 'Bank',
			'description' => 'first show data',
			'type' => '0',
			'list_number' => 20,
			'icon' => 'fa-bank',
		]);
		modul::create([
			'id' => str::Uuid(36),
			'url' => 'server',
			'name' => 'Server',
			'description' => 'first show data',
			'type' => '0',
			'list_number' => 21,
			'icon' => 'fa-cloud',
		]);
		modul::create([
			'id' => str::Uuid(36),
			'url' => 'prefixgroup',
			'name' => 'Prefix Group',
			'description' => 'first show data',
			'type' => '0',
			'list_number' => 22,
			'icon' => 'fa-life-saver',
		]);

		// Customer
        $Customer_Uuid = str::Uuid(36);
        modul::create([
            'id' => $Customer_Uuid,
			'url' => '#',
			'name' => 'Customer Setting',
			'description' => 'first show data',
			'type' => '0',
			'list_number' => 30,
			'icon' => 'fa-qrcode',
        ]);
			modul::create([
				'id' => str::Uuid(36),
				'url' => 'customergroup',
				'm_id' => $Customer_Uuid,
				'name' => 'Customer Group',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 21,
				'icon' => '-',
			]);
			modul::create([
				'id' => str::Uuid(36),
				'url' => 'customer',
				'm_id' => $Customer_Uuid,
				'name' => 'Customer',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 22,
				'icon' => '-',
			]);

		// Project
        $Project_Uuid = str::Uuid(36);
		modul::create([
			'id' => $Project_Uuid,
			'url' => '#',
			'name' => 'Project Setting',
			'description' => 'first show data',
			'type' => '0',
			'list_number' => 40,
			'icon' => 'fa-suitcase',
		]);
			
			modul::create([
				'id' => str::Uuid(36),
				'url' => 'project',
				'm_id' => $Project_Uuid,
				'name' => 'Project',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 41,
				'icon' => '-',
			]);

		// Supplier 
		$Supplier_Uuid = str::Uuid(36);
		modul::create([
			'id' => $Supplier_Uuid,
			'url' => '#',
			'name' => 'Supplier',
			'description' => 'first show data',
			'type' => '0',
			'list_number' => 50,
			'icon' => 'fa-slack',
		]);
			modul::create([
				'id' => str::Uuid(36),
				'url' => 'supplier',
				'm_id' => $Supplier_Uuid,
				'name' => 'Supplier',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 31,
				'icon' => '-',
			]);
			modul::create([
				'id' => str::Uuid(36),
				'url' => 'prefixsupplier',
				'm_id' => $Supplier_Uuid,
				'name' => 'Prefix Supplier',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 32,
				'icon' => '-',
			]);

		// Billing Mapping
		$BillingMapping_Uuid = str::Uuid(36);
		modul::create([
			'id' => $BillingMapping_Uuid,
			'url' => '#',
			'name' => 'Billing Mapping',
			'description' => 'first show data',
			'type' => '0',
			'list_number' => 60,
			'icon' => 'fa-sitemap',
		]);
			modul::create([
				'id' => str::Uuid(36),
				'url' => 'serversites',
				'm_id' => $BillingMapping_Uuid,
				'name' => 'Server',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 43,
				'icon' => '-',
			]);
			modul::create([
				'id' => str::Uuid(36),
				'url' => 'prefixsites',
				'm_id' => $BillingMapping_Uuid,
				'name' => 'Prefix',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 44,
				'icon' => '-',
			]);
			modul::create([
				'id' => str::Uuid(36),
				'url' => 'extensionsites',
				'm_id' => $BillingMapping_Uuid,
				'name' => 'Extension',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 45,
				'icon' => '-',
			]);

		// Monitoring
		$Monitoring_Uuid = str::Uuid(36);
		modul::create([
			'id' => $Monitoring_Uuid,
			'url' => '#',
			'name' => 'Monitoring',
			'description' => 'first show data',
			'type' => '0',
			'list_number' => 60,
			'icon' => 'fa-sitemap',
		]);
			// modul::create([
			// 	'id' => str::Uuid(36),
			// 	'url' => 'serversites',
			// 	'm_id' => $Monitoring_Uuid,
			// 	'name' => 'Server',
			// 	'description' => 'first show data',
			// 	'type' => '0',
			// 	'list_number' => 61,
			// 	'icon' => '-',
			// ]);
			// modul::create([
			// 	'id' => str::Uuid(36),
			// 	'url' => 'prefixsites',
			// 	'm_id' => $Monitoring_Uuid,
			// 	'name' => 'Prefix',
			// 	'description' => 'first show data',
			// 	'type' => '0',
			// 	'list_number' => 62,
			// 	'icon' => '-',
			// ]);
	        modul::create([
	            'id' => str::Uuid(36),
				'url' => 'filelog',
				'm_id' => $Monitoring_Uuid,
				'name' => 'File Log',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 63,
				'icon' => '-',
	        ]);

		// Manage
        // $Manage_Uuid = str::Uuid(36);
        // modul::create([
        //     'id' => $Manage_Uuid,
		// 	'url' => '#',
		// 	'name' => 'Manage',
		// 	'description' => 'first show data',
		// 	'type' => '0',
		// 	'list_number' => 40,
		// 	'icon' => 'fa-gear',
        // ]);
	        // modul::create([
	        //     'id' => str::Uuid(36),
			// 	'url' => 'request',
			// 	'm_id' => $Manage_Uuid,
			// 	'name' => 'Request',
			// 	'description' => 'first show data',
			// 	'type' => '0',
			// 	'list_number' => 34,
			// 	'icon' => '-',
	        // ]);
	        // modul::create([
	        //     'id' => str::Uuid(36),
			// 	'url' => 'number',
			// 	'm_id' => $Manage_Uuid,
			// 	'name' => 'Number',
			// 	'description' => 'first show data',
			// 	'type' => '0',
			// 	'list_number' => 35,
			// 	'icon' => '-',
	        // ]);
	        // modul::create([
	        //     'id' => str::Uuid(36),
			// 	'url' => 'nontraffic',
			// 	'm_id' => $Manage_Uuid,
			// 	'name' => 'Nontraffic',
			// 	'description' => 'first show data',
			// 	'type' => '0',
			// 	'list_number' => 39,
			// 	'icon' => '-',
	        // ]);

		// Master Data 
        $Master_Uuid = str::Uuid(36);
        modul::create([
            'id' => $Master_Uuid,
			'url' => '#',
			'name' => 'Master Data',
			'description' => 'first show data',
			'type' => '0',
			'list_number' => 991,
			'icon' => 'fa-desktop',
        ]);
	        # Condition
	        // $cond = array();
	        // $cond[] = ['name', 'Master Data'];
	        // $data_result = modul::where($cond)->first();
	        // $Uuid = $data_result->id;
            $Management_Uuid = str::Uuid(36);
	        modul::create([
	            'id' => $Management_Uuid,
				'url' => '#',
				'm_id' => $Master_Uuid,
				'name' => 'User',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 9911,
				'icon' => '-',
	        ]);
		        // $cond = array();
		        // $cond[] = ['name', 'User Management'];
		        // $data_result = modul::where($cond)->first();
		        // $Uuid = $data_result->id;
		        modul::create([
		            'id' => str::Uuid(36),
					'url' => 'employee',
					'm_id' => $Management_Uuid,
					'name' => 'Employee',
					'description' => 'first show data',
					'type' => '0',
					'list_number' => 99111,
					'icon' => '-',
		        ]);
		        modul::create([
		            'id' => str::Uuid(36),
					'url' => 'worklocation',
					'm_id' => $Management_Uuid,
					'name' => 'Work Location',
					'description' => 'first show data',
					'type' => '0',
					'list_number' => 99112,
					'icon' => '-',
		        ]);
				modul::create([
					'id' => str::Uuid(36),
					'url' => 'department',
					'm_id' => $Management_Uuid,
					'name' => 'Department',
					'description' => 'first show data',
					'type' => '0',
					'list_number' => 99113,
					'icon' => '-',
				]);

			$SystemManagement_Uuid = str::Uuid(36);
			modul::create([
				'id' => $SystemManagement_Uuid,
				'url' => '#',
				'm_id' => $Master_Uuid,
				'name' => 'Management',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 9912,
				'icon' => '-',
			]);
				modul::create([
					'id' => str::Uuid(36),
					'url' => 'businessentity',
					'm_id' => $SystemManagement_Uuid,
					'name' => 'Business Entity',
					'description' => 'first show data',
					'type' => '0',
					'list_number' => 99122,
					'icon' => '-',
				]);
				// modul::create([
				// 	'id' => str::Uuid(36),
				// 	'url' => 'invoicepriority',
				// 	'm_id' => $SystemManagement_Uuid,
				// 	'name' => 'Invoice Priority',
				// 	'description' => 'first show data',
				// 	'type' => '0',
				// 	'list_number' => 99123,
				// 	'icon' => '-',
				// ]);
				// modul::create([
				// 	'id' => str::Uuid(36),
				// 	'url' => 'statusproject',
				// 	'm_id' => $SystemManagement_Uuid,
				// 	'name' => 'Status Project',
				// 	'description' => 'first show data',
				// 	'type' => '0',
				// 	'list_number' => 9924,
				// 	'icon' => '-',
				// ]);
				// modul::create([
				// 	'id' => str::Uuid(36),
				// 	'url' => 'operatorseluler',
				// 	'm_id' => $SystemManagement_Uuid,
				// 	'name' => 'Operator Seluler',
				// 	'description' => 'first show data',
				// 	'type' => '0',
				// 	'list_number' => 9925,
				// 	'icon' => '-',
				// ]);

		// Settings
		$Settings_Uuid = str::Uuid(36);
        modul::create([
            'id' => $Settings_Uuid,
			'url' => '#',
			'name' => 'Settings',
			'description' => 'first show data',
			'type' => '0',
			'list_number' => 992,
			'icon' => 'fa-cogs',
        ]);
	        modul::create([
	            'id' => str::Uuid(36),
				'url' => 'profile',
				'm_id' => $Settings_Uuid,
				'name' => 'Profile',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 9921,
				'icon' => '-',
	        ]);
	        modul::create([
	            'id' => str::Uuid(36),
				'url' => 'syssetting',
				'm_id' => $Settings_Uuid,
				'name' => 'System Setting',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 9922,
				'icon' => '-',
	        ]);
	        modul::create([
	            'id' => str::Uuid(36),
				'url' => 'menu',
				'm_id' => $Settings_Uuid,
				'name' => 'Menu',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 9923,
				'icon' => '-',
	        ]);
	        modul::create([
	            'id' => str::Uuid(36),
				'url' => 'role',
				'm_id' => $Settings_Uuid,
				'name' => 'Role',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 9924,
				'icon' => '-',
	        ]);
	        modul::create([
	            'id' => str::Uuid(36),
				'url' => 'user',
				'm_id' => $Settings_Uuid,
				'name' => 'User',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 9925,
				'icon' => '-',
	        ]);

		// Report
		$Settings_Uuid = str::Uuid(36);
        modul::create([
            'id' => $Settings_Uuid,
			'url' => '#',
			'name' => 'Report',
			'description' => 'first show data',
			'type' => '0',
			'list_number' => 993,
			'icon' => 'fa-bar-chart-o',
        ]);
	        modul::create([
	            'id' => str::Uuid(36),
				'url' => 'reportcdr',
				'm_id' => $Settings_Uuid,
				'name' => 'CDR',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 9931,
				'icon' => '-',
	        ]);
	        modul::create([
	            'id' => str::Uuid(36),
				'url' => 'reportinvoice',
				'm_id' => $Settings_Uuid,
				'name' => 'Invoice',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 9932,
				'icon' => '-',
	        ]);
	        modul::create([
	            'id' => str::Uuid(36),
				'url' => 'reportusagecustomer',
				'm_id' => $Settings_Uuid,
				'name' => 'Usage Customer',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 9932,
				'icon' => '-',
	        ]);
	        modul::create([
	            'id' => str::Uuid(36),
				'url' => 'reportbiayacustomer',
				'm_id' => $Settings_Uuid,
				'name' => 'Biaya Customer',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 9932,
				'icon' => '-',
	        ]);
	        modul::create([
	            'id' => str::Uuid(36),
				'url' => 'reportnewip',
				'm_id' => $Settings_Uuid,
				'name' => 'New IP',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 9932,
				'icon' => '-',
	        ]);
	        modul::create([
	            'id' => str::Uuid(36),
				'url' => 'reportusagesupplier',
				'm_id' => $Settings_Uuid,
				'name' => 'Usage Supplier',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 9932,
				'icon' => '-',
	        ]);

		// Executive View
		$ExecutiveView_Uuid = str::Uuid(36);
        modul::create([
            'id' => $ExecutiveView_Uuid,
			'url' => '#',
			'name' => 'Executive View',
			'description' => 'first show data',
			'type' => '0',
			'list_number' => 994,
			'icon' => 'fa-bar-chart-o',
        ]);
	        modul::create([
	            'id' => str::Uuid(36),
				'url' => 'exreportcdr',
				'm_id' => $ExecutiveView_Uuid,
				'name' => 'CDR',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 9941,
				'icon' => '-',
	        ]);
	        modul::create([
	            'id' => str::Uuid(36),
				'url' => 'exreportinvoice',
				'm_id' => $ExecutiveView_Uuid,
				'name' => 'Invoice',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 9942,
				'icon' => '-',
	        ]);
	        modul::create([
	            'id' => str::Uuid(36),
				'url' => 'exreportusagecustomer',
				'm_id' => $ExecutiveView_Uuid,
				'name' => 'Usage Customer',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 9942,
				'icon' => '-',
	        ]);
	        modul::create([
	            'id' => str::Uuid(36),
				'url' => 'exreportbiayacustomer',
				'm_id' => $ExecutiveView_Uuid,
				'name' => 'Biaya Customer',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 9942,
				'icon' => '-',
	        ]);
	        modul::create([
	            'id' => str::Uuid(36),
				'url' => 'exreportnewip',
				'm_id' => $ExecutiveView_Uuid,
				'name' => 'New IP',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 9942,
				'icon' => '-',
	        ]);
	        modul::create([
	            'id' => str::Uuid(36),
				'url' => 'exreportusagesupplier',
				'm_id' => $ExecutiveView_Uuid,
				'name' => 'Usage Supplier',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 9942,
				'icon' => '-',
	        ]);

		// Logs
		$Settings_Uuid = str::Uuid(36);
        modul::create([
            'id' => $Settings_Uuid,
			'url' => '#',
			'name' => 'Logs',
			'description' => 'first show data',
			'type' => '0',
			'list_number' => 999,
			'icon' => 'fa-puzzle-piece',
        ]);
	        modul::create([
	            'id' => str::Uuid(36),
				'url' => 'activity',
				'm_id' => $Settings_Uuid,
				'name' => 'Activity',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 9992,
				'icon' => '-',
	        ]);
	        modul::create([
	            'id' => str::Uuid(36),
				'url' => 'system',
				'm_id' => $Settings_Uuid,
				'name' => 'System',
				'description' => 'first show data',
				'type' => '0',
				'list_number' => 9993,
				'icon' => '-',
	        ]);
    }
}
