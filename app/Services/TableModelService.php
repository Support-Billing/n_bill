<?php

// app/Services/MyService.php

namespace App\Services;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\customer_ip_prefix;
use App\Models\Project;
use App\Models\Prefix;
use App\Models\CustomerPrice;

class TableModelService 
{
    

    // sebelumnya namanya ini project_prefix_ip
    public function get_customer_ip_prefix_by_prefix() {
    
        $project_prefixs = customer_ip_prefix::get();
        $getProject = array();
        $getProjectPrefix = array();
        foreach ($project_prefixs as $key => $val) {
            $getProject[$val->prefix] = $val->idxCustomer;
            $getProjectPrefix[$val->prefix] = $val->idx;
            
        }
        $data['DataIDProject'] = $getProject;
        $data['DataIDProjectPrefix'] = $getProjectPrefix;
        
        return $data;
    }
    
    public function dest_no_project_prefix_name() {
        
        $prefixes = Prefix::get();
        $getDestNoPrefixName_premium = array();
        $getDestNoPrefixName_pstn = array();
        $getDestNoPrefixName_all = array();
        foreach ($prefixes as $key => $val) {
            $data = $val->prefixName;
            switch (true) {
                case stripos($data, "Premium") !== false:
                    $getDestNoPrefixName_premium[$val->prefixNumber] = $val->prefixName;
                    break;
                case stripos($data, "PSTN") !== false:
                    $getDestNoPrefixName_pstn[$val->prefixNumber] = $val->prefixName;
                    break;
            }
            $getDestNoPrefixName_all[$val->prefixNumber] = $val->prefixName;
        }
        
        $data['DataDestNoPrefixName_premium'] = $getDestNoPrefixName_premium;
        $data['DataDestNoPrefixName_pstn'] = $getDestNoPrefixName_pstn;
        $data['DataDestNoPrefixName_all'] = $getDestNoPrefixName_all;
        
        return $data;
            
    }

    public function project_CustomerPrice(){
        
        $CustomerPrices = CustomerPrice::get();
        $getDataCustomerPrice = array();
        foreach ($CustomerPrices as $key => $val) {
            $getDataCustomerPrice[$val->idxCustomer][$val->prefixName] = $val->tarifPerMenit;
        }
        $data['DataCustomerPrice'] = $getDataCustomerPrice;
        
        return $data;
            
    }

    public function project_Customer(){
    
        $Projects = Project::get();
        $getDataCustomer = array();
        foreach ($Projects as $key => $val) {
            $getDataCustomer[$val->projectID] = $val->idxCustomer;
        }
        
        $data['DataIDCustomer'] = $getDataCustomer;
        
        return $data;
        
    }

}
