<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\syssetting;
use Illuminate\Support\str;

class SyssettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Business Entity
        syssetting::create([
            'id' => str::Uuid(36),
            'key' => "BU",
            'name' => "Perseroan Terbatas",
            'value' => "PT",
            'description' => "Business Entity - Perseroan Terbatas (menu Customer) "
        ]);
        syssetting::create([
            'id' => str::Uuid(36),
            'key' => "BU",
            'name' => "Commanditaire Vennootschap",
            'value' => "CV",
            'description' => "Business Entity - Commanditaire Vennootschap (menu Customer) "
        ]);

        // Order List Customer
        syssetting::create([
            'id' => str::Uuid(36),
            'key' => "OLC",
            'name' => "Newest",
            'value' => "newest",
            'description' => "Order List Customer - Newest (menu Customer sebagai category) "
        ]);
        syssetting::create([
            'id' => str::Uuid(36),
            'key' => "OLC",
            'name' => "Last Update",
            'value' => "update",
            'description' => "Order List Customer - Last Update (menu Customer sebagai category) "
        ]);
        syssetting::create([
            'id' => str::Uuid(36),
            'key' => "OLC",
            'name' => "Priority",
            'value' => "priority",
            'description' => "Order List Customer - Priority (menu Customer sebagai category) "
        ]);
        syssetting::create([
            'id' => str::Uuid(36),
            'key' => "OLC",
            'name' => "Medium",
            'value' => "medium",
            'description' => "Order List Customer - Medium (menu Customer sebagai category) "
        ]);
        syssetting::create([
            'id' => str::Uuid(36),
            'key' => "OLC",
            'name' => "Regular",
            'value' => "regular",
            'description' => "Order List Customer - Regular (menu Customer sebagai category) "
        ]);
        syssetting::create([
            'id' => str::Uuid(36),
            'key' => "OLC",
            'name' => "Tiering",
            'value' => "tiering",
            'description' => "Order List Customer - Tiering (menu Customer sebagai category) "
        ]);

        // status Data Customer
        syssetting::create([
            'id' => str::Uuid(36),
            'key' => "SDC",
            'name' => "Non Active",
            'value' => "0",
            'description' => "Order List Customer - Tiering (menu Customer sebagai status Data Customer) "
        ]);
        syssetting::create([
            'id' => str::Uuid(36),
            'key' => "SDC",
            'name' => "Active",
            'value' => "1",
            'description' => "Order List Customer - Tiering (menu Customer sebagai status Data Customer) "
        ]);
        syssetting::create([
            'id' => str::Uuid(36),
            'key' => "SDC",
            'name' => "Delete",
            'value' => "2",
            'description' => "Order List Customer - Tiering (menu Customer sebagai status Data Customer) "
        ]);
        
        // Invoice Priority
        syssetting::create([
            'id' => str::Uuid(36),
            'key' => "IPY",
            'name' => "Regular",
            'value' => "0",
            'description' => "Order List Customer - Tiering (menu Customer sebagai Invoice Priority) "
        ]);
        syssetting::create([
            'id' => str::Uuid(36),
            'key' => "IPY",
            'name' => "Priority",
            'value' => "1",
            'description' => "Order List Customer - Tiering (menu Customer sebagai Invoice Priority) "
        ]);
        syssetting::create([
            'id' => str::Uuid(36),
            'key' => "IPY",
            'name' => "Medium",
            'value' => "2",
            'description' => "Order List Customer - Tiering (menu Customer sebagai Invoice Priority) "
        ]);

        // Server Type 
        syssetting::create([
            'id' => str::Uuid(36),
            'key' => "STB",
            'name' => "MERA",
            'value' => "MERA",
            'description' => "Server Type Billing - Server (menu Server sebagai Server Type) "
        ]);
        syssetting::create([
            'id' => str::Uuid(36),
            'key' => "STB",
            'name' => "ELASTIX",
            'value' => "ELASTIX",
            'description' => "Server Type Billing - Server (menu Server sebagai Server Type) "
        ]);
        syssetting::create([
            'id' => str::Uuid(36),
            'key' => "STB",
            'name' => "VOS",
            'value' => "VOS",
            'description' => "Server Type Billing - Server (menu Server sebagai Server Type) "
        ]);

        // Default Data 
        syssetting::create([
            'id' => str::Uuid(36),
            'key' => "DFT",
            'name' => "Not Default",
            'value' => "Not Default",
            'description' => "Server Type Billing - Server (menu Server sebagai Server Type) "
        ]);
        syssetting::create([
            'id' => str::Uuid(36),
            'key' => "DFT",
            'name' => "As Default",
            'value' => "As Default",
            'description' => "Server Type Billing - Server (menu Server sebagai Server Type) "
        ]);

        // Default Protocol 
        syssetting::create([
            'id' => str::Uuid(36),
            'key' => "PTL",
            'name' => "SSH File Transfer Protocol (SFTP)",
            'value' => "sftp",
            'description' => "Protocol - SSH File Transfer Protocol (menu Server sebagai Protocol) "
        ]);
    }
}
