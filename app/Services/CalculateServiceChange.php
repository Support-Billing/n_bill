<?php

namespace App\Services;
use Illuminate\Support\Facades\Auth;
use DB;

class CalculateService
{
    public function regex_clear_data( $string,  $pattern_regex){
        
        $clear_data = 'Tidak ada nilai setelah melakukan parser.';
        if(!empty($string) && !empty($pattern_regex)){
            if ($this->isRegexValid($pattern_regex)) {
                if (preg_match($pattern_regex, $string, $matches)) {
                    if (isset($matches[1])) {
                        $clear_data = $matches[1];
                        $clear_data = preg_replace('/"/', '', $clear_data);;
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
    
    public function readMERA($valuesArray, $setting_parser) {
        
        $columns = ['datetime', 'sourceNo','sourceNoOut','sourceIP','elapsedTime','destNo','destNoOut','destIP','destName', 'destIPOnly', 'sourceIPOnly'];
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
    
    public function readVOS($valuesArray, $setting_parser) {
        $columns = ['datetime', 'sourceNo','sourceNoOut','sourceIP','elapsedTime','destNo','destNoOut','destIP','destName', 'destIPOnly', 'sourceIPOnly'];
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
    public function readEDirect($valuesArray, $setting_parser) {
        $columns = ['datetime', 'sourceNo','sourceNoOut','sourceIP','elapsedTime','destNo','destNoOut','destIP','destName', 'destIPOnly', 'sourceIPOnly'];
        foreach ($columns as $column) {
            
            switch ($column) {
                case 'sourceNo':
                    $stringSourceNo = $this->getColumnData($valuesArray, $setting_parser, $column);
                    if (preg_match('/\+?(\d+)/', $stringSourceNo, $matches)) {
                        $stringSourceNo = $matches[1];
                    }
                    $cdrdata[$column] = $stringSourceNo;
                    break;
                default:
                    $cdrdata[$column] = $this->getColumnData($valuesArray, $setting_parser, $column);
                    break;
            }
        }
        return $cdrdata;
    }
}