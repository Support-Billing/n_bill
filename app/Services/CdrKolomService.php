<?php

namespace App\Services;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;

class CdrKolomService
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
            // echo 'rowValue';
            // print_r ($rowValue);
            // echo 'regex';
            // print_r ($regex);
            // exit;
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
    
    // =========================================================== MERA
    public function datetimeMERA($valuesArray, $valuesString, $setting_parser) {
        
        if ($setting_parser->teknik_parser == 'column'){
            $clear_data_datetime = $this->getColumnData($valuesArray, $setting_parser, 'datetime');
        } else {
            $clear_data_datetime = $this->getRowData($valuesString, $setting_parser, 'datetime');
        }
        // echo $clear_data_datetime;exit;
        $parts = explode(' ', $clear_data_datetime);
        $time = $parts[3];  // Jam, menit, dan detik
        $year = $parts[4];  // Tahun
        $month = $parts[1]; // Nama bulan
        $day = $parts[2];   // Tanggal
        $getDate = date('Y-m-d H:i:s', strtotime("$year-$month-$day $time"));
        // echo $getDate;exit;
        
        return $getDate;
    }

    public function generalKolomMERA($valuesArray, $valuesString, $setting_parser, $column) {
        
        if ($setting_parser->teknik_parser == 'column'){
            $value = $this->getColumnData($valuesArray, $setting_parser, $column);
        } else {
            $value = $this->getRowData($valuesString, $setting_parser, $column);
        }
        
        return $value;
    }
    
    // =========================================================== VOS
    public function datetimeVOS($valuesArray, $setting_parser) {
        
        $clear_data_datetime = $this->getColumnData($valuesArray, $setting_parser, 'datetime');
        $timestamp = $clear_data_datetime / 1000;
        $fdatetime = date("Y-m-d H:i:s", $timestamp);
        
        return $fdatetime;
    }
    
    public function sourceNoVOS($valuesArray, $setting_parser) {
        
        $sourceNo = $this->getColumnData($valuesArray, $setting_parser, 'sourceNo');
        
        return $sourceNo;
    }

    
    public function generalKolomVOS($valuesArray, $setting_parser, $column) {
        
        $value = $this->getColumnData($valuesArray, $setting_parser, $column);
        
        return $value;
    }

    // =========================================================== Direct
    public function datetimeDirect($valuesArray, $setting_parser, $column) {
        
        $stringData = $this->getColumnData($valuesArray, $setting_parser, $column);

        // Periksa apakah stringData mengandung tanda "/"
        if (strpos($stringData, '/') !== false) {
            // Jika ada "/", lanjutkan dengan ekstraksi datetime
            // Pecah string menjadi array berdasarkan tanda "/"
            $dataArray = explode('/', $stringData);

            // Ambil bagian datetime dari array
            $stringDatetime = end($dataArray);

            // Ubah string datetime menjadi format yang sesuai
            $datetime = \DateTime::createFromFormat('YmdHisu', $stringDatetime); // Perhatikan penggunaan \ sebelum DateTime

            // Lakukan format jika perlu
            $formattedDatetime = $datetime ? $datetime->format('Y-m-d H:i:s') : $stringData; // Perhatikan pengecekan nullability untuk $datetime
        } else {
            // Jika tidak ada "/", berikan pesan error atau tindakan yang sesuai
            $formattedDatetime = $stringData;
        }

        return $formattedDatetime;

    }

    public function generalKolomDirect($valuesArray, $setting_parser, $column) {
        
        $value = $this->getColumnData($valuesArray, $setting_parser, $column);
        
        return $value;

    }

    public function sourceNoDirect($valuesArray, $setting_parser) {
        $stringSourceNo = '';
        $getColumnData = $this->getColumnData($valuesArray, $setting_parser, 'sourceNo');
        
        // Memeriksa apakah stringSourceNo adalah nomor telepon dalam tanda kurung < >
        if (preg_match('/<(\d+)>/', $getColumnData, $matches) && isset($matches[1])) {
            $stringSourceNo = $matches[1];
        } else {
            // Jika tidak ada nomor telepon dalam tanda kurung, coba cari nomor telepon tanpa tanda kurung
            if (preg_match('/(\d+)/', $getColumnData, $matches) && isset($matches[1])) {
                $stringSourceNo = $matches[1];
            } else {
                // Jika tidak ada nomor telepon, gunakan nilai default
                $stringSourceNo = $getColumnData;
            }
        }
        
        return $stringSourceNo;
    }
    
    
}