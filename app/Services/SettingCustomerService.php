<?php

namespace App\Services;
use Illuminate\Support\Facades\Auth;
use DB;

class SettingCustomerService
{
    
    public function get_project_CustomerIp() {
        $CustomerIps = CustomerIp::get();
        $getDataCustomerIp = array();
        $getDataCustomerIpValue = array();
        $getDataCustomerIpFixed = array();
        foreach ($CustomerIps as $key => $val) {
            $getDataCustomerIp[$val->idxCustomer][$val->startIPOnly] = $val->idx;
            $getDataCustomerIpValue[$val->idxCustomer][$val->startIPOnly] = $val->startIPValue;
            $getDataCustomerIpFixed[$val->idxCustomer][$val->startIPOnly] = $val->startIPFixed;
        }
        $this->_DataCustomerIp = $getDataCustomerIp;
        $this->_DataSourceIpValue = $getDataCustomerIpValue;
        $this->_DataSourceIpFixed = $getDataCustomerIpFixed;
        
        return 'succsess';
    }

    public function get_customer_ip_by_prefix() {
        $project_prefixs = CustomerIpPrefix::get();
        $getProject = array();
        $getProjectPrefix = array();
        foreach ($project_prefixs as $key => $val) {
            $getProject[$val->prefix] = $val->idxCustomer;
            $getProjectPrefix[$val->prefix] = $val->idx;
            
        }
        $this->_DataIDProject = $getProject;
        $this->_DataCustomerIpPrefix = $getProjectPrefix;
        
        return 'succsess';
    }

    public function get_project_CustomerPrice() {
        $CustomerPrices = CustomerPrice::get();
        $getDataCustomerPrice = array();
        foreach ($CustomerPrices as $key => $val) {
            $getDataCustomerPrice[$val->idxCustomer][$val->prefixName] = $val->tarifPerMenit;
        }
        $this->_DataCustomerPrice = $getDataCustomerPrice;
        
        return 'succsess';
    }
    
    // informasi detil customer
    private function getDataDefaultResult($data_default_result, $hitung = 0){
        // destNoPrefix || dimungkinkan berulang
        $real_destNo = $data_default_result['destNo'];
        $real_idxCustomer = $data_default_result['idxCustomer'];
        $_temp_destNoPrefix = substr($data_default_result['destNo'], 4, $hitung);
        $_temp_destNoPrefix = '62' . substr($_temp_destNoPrefix, 1);
        

        if (isset($this->_DataDestNoPrefixName_all[$_temp_destNoPrefix])) {
            $_temp_destNoPrefixName = $this->_DataDestNoPrefixName_all[$_temp_destNoPrefix];
            $BuatNgecekDulu = [
                'destNoPrefix' => $_temp_destNoPrefix,
                'destNoPrefixName' => $_temp_destNoPrefixName
            ];
            if (isset($this->_DataCustomerPrice[$real_idxCustomer][$_temp_destNoPrefixName])) {
                
                $_temp_custPrice = $this->_DataCustomerPrice[$real_idxCustomer][$_temp_destNoPrefixName];
                $_temp_custTime = $data_default_result['elapsedTime'];
                
                if (preg_match("/\bpremium\b/i", $_temp_destNoPrefixName)) {
                    $hasil = $_temp_custTime / 60;
                    $hasil_bulat = round($hasil);
                    $roundCustTimeData = $hasil_bulat * 60;
                    $_temp_custTime = $roundCustTimeData;
                }
                

                $BuatNgecekDulu = [
                    'destNoPrefix' => $_temp_destNoPrefix,
                    'destNoPrefixName' => $_temp_destNoPrefixName,
                    'custPrice' => $_temp_custPrice,
                    'custTime' => $_temp_custTime,
                ];
                
            }else{
                $LineNumber = $data_default_result['LineNumber'];
                $BuatNgecekDulu = [
                    'destNoRealPrefix' => $_temp_destNoPrefix,
                    'destNoRealPrefixName' => $_temp_destNoPrefixName,
                ];
                $data_default_result = array_merge($data_default_result, $BuatNgecekDulu); 
                return $this->getDataDefaultResult($data_default_result, $hitung-1);
            }
        }else{
            return $this->getDataDefaultResult($data_default_result, $hitung-1);
        }
        
        if (isset($BuatNgecekDulu)) {
            $data_default_result = array_merge($data_default_result, $BuatNgecekDulu); 
            return $data_default_result;
        }else{
            return $data_default_result;
        }
        
    }
}