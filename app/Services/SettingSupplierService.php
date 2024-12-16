<?php

namespace App\Services;
use Illuminate\Support\Facades\Auth;
use DB;

class SettingSupplierService
{
    private function getDataSupp($call_data_server){
        $destName = $call_data_server['destName'] ;
        foreach($this->_DataIDSupplier as $key => $val){
            if(preg_match("/\b.*$key.*\b/i", $destName)){
                return $val;
            }
        }
        
        return false;
    }
    
    public function get_Supplier() {
        $Suppliers = Supplier::get();
        $getDataSupplier = array();
        foreach ($Suppliers as $key => $val) {
            if (isset($val->like)){
                $getDataSupplier[$val->like] = $val->idx;
            }
        }
        $this->_DataIDSupplier = $getDataSupplier;
        
        return 'succsess';
    }

    public function get_SupplierIp() {
        $SupplierIps = SupplierIp::get();
        $getDataSupplierIp = array();
        $getDataSupplierIpFixed = array();
        $getDataSupplierIpValue = array();
        foreach ($SupplierIps as $key => $val) {
            $getDataSupplierIp[$val->idxSupplier][$val->startIP] = $val->idx;
            $getDataSupplierIpFixed[$val->idxSupplier][$val->startIP] = $val->startIPFixed;
            $getDataSupplierIpValue[$val->idxSupplier][$val->startIP] = $val->startIPValue;
        }
        $this->_DataSupplierIp = $getDataSupplierIp;
        $this->_DataSupplierIpFixed = $getDataSupplierIpFixed;
        $this->_DataSupplierIpValue = $getDataSupplierIpValue;
        
        return 'succsess';
    }
    
    public function getSupplierPrice() {
        $SupplierPrices = SupplierPrice::get();
        $getDataSupplierPrice = array();
        foreach ($SupplierPrices as $key => $val) {
            $getDataSupplierPrice[$val->idxSupplier][$val->prefixName] = $val->tarifPerMenit;
        }
        $this->_DataSupplierPrice = $getDataSupplierPrice;
        
        return 'succsess';
    }
    
    public function get_supplier_ip_by_prefix() {
        $SupplierIpPrefixs = SupplierIpPrefix::get();
        $getSupplierPrefixByIdxSupplier = array();
        $getSupplierPrefixByPrefix = array();
        $getSupplierPrefixByIdxSupplierPrefix = array();
        foreach ($SupplierIpPrefixs as $key => $val) {
            $getSupplierPrefixByIdxSupplier[$val->idxSupplier] = $val->idx;
            $getSupplierPrefixByPrefix[$val->prefix] = $val->idx;
            $getSupplierPrefixByIdxSupplierPrefix[$val->idxSupplier][$val->prefix] = $val->idx;
            
        }
        $this->_DataSupplierPrefixByIdxSupplier = $getSupplierPrefixByIdxSupplier;
        $this->_DataSupplierPrefixByPrefix = $getSupplierPrefixByPrefix;
        $this->_DataSupplierPrefixByIdxSupplierPrefix = $getSupplierPrefixByIdxSupplierPrefix;
        
        return 'succsess';
    }
}