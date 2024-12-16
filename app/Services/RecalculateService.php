<?php

namespace App\Services;
use Illuminate\Support\Facades\Auth;
use App\Models\ProjectPrefixIp;
use DB;

class RecalculateService
{
    private $_prefixDataProject = null;
    private $_prefixDataProjectIP = null;

    public function __construct()
    {        
        $this->project_prefix_ip();
    }
    
    private function project_prefix_ip() {
        if ($this->_prefixDataProject === null) {
            $project_prefix_ips = ProjectPrefixIp::get();
            $getProject = array();
            $getProjectIp = array();
            foreach ($project_prefix_ips as $key => $val) {
                $getProject[$val->prefix] = $val->projectID;
                $getProjectIp[$val->prefix] = $val->idxProjectIP;
            }
            $this->_prefixDataProject = $getProject;
            $this->_prefixDataProjectIP = $getProjectIp;
        }
        return 'succsess';
    }
    
    public function regex_clear_data( $string,  $pattern_regex){
        
        $clear_data = 'Tidak ada nilai setelah melakukan parser.';
        if(!empty($string) && !empty($pattern_regex)){
            if ($this->isRegexValid($pattern_regex)) {
                if (preg_match($pattern_regex, $string, $matches)) {
                    if (isset($matches[1])) {
                        $clear_data = $matches[1];
                    }
                }
            } else {
                $clear_data = "Pattern Regex tidak valid";
            }
        }

        return $clear_data;
    }

    private function isRegexValid($pattern) {
        // Memeriksa apakah pola regex valid
        if (@preg_match($pattern, '') === false) {
            // Mengambil kode kesalahan
            $errorCode = preg_last_error();
            // echo $errorCode;exit;
            // Memeriksa apakah kesalahan adalah karena tidak ada delimiter
            // if ($errorCode === PREG_NO_ERROR || $errorCode === PREG_NO_DELIMITER) {
            if ($errorCode) {
                return false;
            }
        }
    
        return true;
    }

    private function getRowData($rowValue, $setting_parser, $columnName) {
        $regex_columnName = 'regex_'.$columnName;
        $regex = $setting_parser->setting_parser_regex->$regex_columnName;
        if (!empty($regex)) {
            $clearData = $this->regex_clear_data($rowValue, $regex);
        }else{
            $clearData = '-';
        }
        return $clearData;
    }

    private function getRowData2($rowValue, $setting_parser, $columnName) {
        $regex_columnName = 'regex_'.$columnName;
        $regex = $setting_parser->setting_parser_regex->$regex_columnName;
        if (!empty($regex)) {
            $clearData = $this->regex_clear_data($rowValue, $regex);
        }else{
            $clearData = '-';
        }
        echo $clearData;
        return $clearData;
    }

    private function getColumnData($valuesArray, $setting_parser, $columnName) {
        $columnIndex = intval(substr($setting_parser->$columnName, 1)) - 1;
        $clearData = '';
        if ($columnIndex >= 0 && $columnIndex < count($valuesArray)) {
            $columnValue = $valuesArray[$columnIndex];
            $regex_columnName = 'regex_'.$columnName;
            $regex = $setting_parser->setting_parser_regex->$regex_columnName;
    
            if (!empty($regex)) {
                $clearData = $this->regex_clear_data($columnValue, $regex);
            }else{
                $clearData = $columnValue;
            }
        }
        
        return $clearData;
    }

    private function readMERA($valuesArray, $setting_parser) {
        
        $columns = ['datetime', 'sourceNo','sourceNoOut','sourceIP','elapsedTime','destNo','destNoOut','destIP','destName', 'sourceIPOnly'];
        foreach ($columns as $column) {

            switch ($column) {
                case 'datetime':
                    if ($setting_parser->teknik_parser == 'column'){
                        $clear_data_datetime = $this->getColumnData($valuesArray, $setting_parser, $column);
                    } else {
                        $clear_data_datetime = $this->getRowData($valuesArray, $setting_parser, $column);
                    }
                    $parts = explode(' ', $clear_data_datetime);
                    // $time = $parts[0];  // Jam, menit, dan detik
                    // $year = $parts[5];  // Tahun
                    // $month = $parts[3]; // Nama bulan
                    // $day = $parts[4];   // Tanggal
                    $time = $parts[3];  // Jam, menit, dan detik
                    $year = $parts[4];  // Tahun
                    $month = $parts[1]; // Nama bulan
                    $day = $parts[2];   // Tanggal
                    $newDate = date('Y-m-d H:i:s', strtotime("$year-$month-$day $time"));
                    $cdrdata[$column] = $newDate;
                break;
                case 'sourceNo':
                    $_data_datasourceNo ='';
                    if ($setting_parser->teknik_parser == 'column'){
                        $_data_datasourceNo = $this->getColumnData($valuesArray, $setting_parser, $column);
                    } else {
                        $_data_datasourceNo = $this->getRowData($valuesArray, $setting_parser, $column);
                    }
                    if($_data_datasourceNo == 'Tidak ada nilai setelah melakukan parser.'){
                        $_data_datasourceNo = $this->getRowData($valuesArray, $setting_parser, 'sourceNoOut');
                    }
                    $cdrdata[$column] = $_data_datasourceNo;
                break;
                default:
                    if ($setting_parser->teknik_parser == 'column'){
                        $cdrdata[$column] = $this->getColumnData($valuesArray, $setting_parser, $column);
                    } else {
                        $cdrdata[$column] = $this->getRowData($valuesArray, $setting_parser, $column);
                    }
                break;
            }
            
        }
        
        return $cdrdata;
        
    }
    
    private function readVOS($valuesArray, $setting_parser) {
        $columns = ['datetime', 'sourceNo','sourceNoOut','sourceIP','elapsedTime','destNo','destNoOut','destIP','destName', 'sourceIPOnly'];
        foreach ($columns as $column) {
            
            switch ($column) {
                case 'datetime':
                    $clear_data_datetime = $this->getColumnData($valuesArray, $setting_parser, $column);
                    $timestamp = $clear_data_datetime / 1000;
                    $fdatetime = date("Y-m-d H:i:s", $timestamp);
                    $cdrdata[$column] = $fdatetime;
                    break;
                default:
                    $cdrdata[$column] = $this->getColumnData($valuesArray, $setting_parser, $column);
                    break;
            }
            
        }
        
        return $cdrdata;
    }
    
    // ASTERISK dan ASTERISK
    private function readEDirect($valuesArray, $setting_parser) {
        $columns = ['datetime', 'sourceNo','sourceNoOut','sourceIP','elapsedTime','destNo','destNoOut','destIP','destName', 'sourceIPOnly'];
        foreach ($columns as $column) {
            $cdrdata[$column] = $this->getColumnData($valuesArray, $setting_parser, $column);
        }
        return $cdrdata;
    }
    
    public function Recalculate($data_result, $setting_parsers)
    {
        
        $dataToInsertCDR = array();
        $dataToInsertRepairCSV = array();
        $dataToInsertRepairCDR = array();
        foreach ($data_result as $value) {

            $parts1 = explode('\\', $value->ReadyPath);
            $result1 = isset($parts1[count($parts1) - 2]) ? $parts1[count($parts1) - 2] : "Format path tidak sesuai pada path 1.";

            $idx = $value->idx;
            $valuesArray = str_getcsv($value->data);
            if (stripos($result1, 'mera') !== false) {
                $jmlKolPHP = 44;
            } else {
                $jmlKolPHP = count($valuesArray);
            }
            
            $setting_parser = array();
            $dataCallback = array();
            
            $dataServer = '';
            unset($ready_push);
            switch ($jmlKolPHP) {
                case 44: // 1
                    $dataServer = 'MERA';
                    $setting_parser = $setting_parsers[0];
                    if ($setting_parser->teknik_parser == 'column'){
                        $call_data_server = $this->readMERA($valuesArray, $setting_parser);
                    } else {
                        $call_data_server = $this->readMERA($value->data, $setting_parser);
                    }
                    // $jmlKolPHP = count($valuesArray);
                    
                    break;
                case 52: // 2
                    $dataServer = 'VOS';
                    $setting_parser = $setting_parsers[1];
                    $call_data_server = $this->readVOS($valuesArray, $setting_parser);
                    break;
                case 18: // 3 & 4
                    $dataServer = 'Direct'; // ini data server ELASTIX dan ASTERISK
                    $setting_parser = $setting_parsers[2];
                    $call_data_server = $this->readEDirect($valuesArray, $setting_parser);
                    break;
                default:
                    break;
            }
            
            $coredata = [
                // 'idx' => $idx,
                'FileName' => $value->FileName,
                'ReadyPath' => $value->ReadyPath,
                'ResultPath' => $value->ResultPath,
                'DateTimeCSVtoDB' => $value->DateTimeCSVtoDB,
                'LineNumber' => $value->LineNumber,
                'serverData' => $dataServer,
                'jmlKol' => $value->jmlKol,
                'jmlKolPHP' => $jmlKolPHP,
                'data' => $value->data,
            ];


            if ($jmlKolPHP == 44 || $jmlKolPHP == 52 || $jmlKolPHP == 18) {
                
                
            // $this->_prefixDataProjectIP = $getProjectIp;
                if (isset($call_data_server['destNo']) && strlen($call_data_server['destNo']) >= 4) {
                    $destNoCustPrefix = substr($call_data_server['destNo'], 0, 4);

                    if (array_key_exists($destNoCustPrefix, $this->_prefixDataProject)) {
                        
                        // Jika $destNoCustPrefix ada dalam $_prefixDataProject
                        $idxCustomer = $this->_prefixDataProject[$destNoCustPrefix];
                    
                        $idxdata = [
                            'idxCustomer' => $idxCustomer
                        ];
                        $ready_push = array_merge($coredata, $call_data_server, $idxdata);
                        $dataToInsertCDR[] = $ready_push;
                    } else {
                        // Jika $destNoCustPrefix tidak ada dalam $_prefixDataProject
                        $ready_push = array_merge($coredata, $call_data_server);
                        $dataToInsertRepairCDR[] = $ready_push;
                    }                
                }
                
            } else {
                $dataToInsertRepairCSV[] = $coredata;
            }
        }
        
        $data['dataToInsertCDR'] = $dataToInsertCDR;
        $data['dataToInsertRepairCSV'] = $dataToInsertRepairCSV;
        // $data['dataToInsertRepairCDR'] = $dataToInsertRepairCDR;

        return $data;
    }
}