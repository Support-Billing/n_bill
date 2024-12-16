<?php

namespace App\Http\Controllers\feature;

use App\Http\Controllers\Controller;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;

use App\Services\MyService;
use App\Services\RecalculateService;

use App\Models\cdr;


use App\Models\monitoring\parser_csv;
use App\Models\monitoring\repaircsv;
use App\Models\monitoring\setting_parser;
use App\Models\ProjectPrefixSrv;
use App\Models\CustomerGroupMember;
use App\Models\ProjectPrefixIp;
use App\Models\Prefix;
use App\Models\CustomerPrice;
use App\Models\ProjectPrice;

class CalculateControllerAmbilCode2nya extends Controller
{

    private $_page_title = 'Calculate CDR';
    private $_url_data = 'reportcdr';
    private $_myService;
    private $_DataIDProjectPrefix = null;
    private $_DataIDProject = null;
    private $_DataIDProjectIP = null;
    private $_DataDestNoPrefixName_premium = null;
    private $_DataDestNoPrefixName_pstn = null;
    private $_DataDestNoPrefixName_all = null;
    private $_DataCustomerGroupMember = null;
    private $_DataCustomerPrice = null;
    private $_DataProjectPrice = null;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_myService = app(MyService::class);
        $this->project_prefix();
        $this->project_prefix_ip();
        $this->dest_no_project_prefix_name();
        $this->project_CustomerGroupMember();
        $this->project_price();
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->_access_menu = $this->_myService->parser_access_menu($this->_url_data);
            return $next($request);
        });
    }

    public function edit_cdr(string $idcore)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            return view('feature.calculate.cdr_calculate', [
                'page_title' => $this->_page_title
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }
    
    public function update_cdr()
    {
        $data_result = parser_csv::limit(1000)->get();
        $setting_parsers = setting_parser::with('setting_parser_regex', 'setting_parser_row')->get();
        $updates = [];
    
        if (!$data_result->isEmpty() && $data_result->count() >= 1 && !$setting_parsers->isEmpty()) {
            $dataToInsert = $this->Calculate($data_result, $setting_parsers);
            
            if (cdr::insert($dataToInsert['dataToInsertCDR']) && repaircsv::insert($dataToInsert['dataToInsertRepairCSV'])) {                               
                // if (parser_csv::limit(1000)->delete()) {
                //     $this->update_cdr();
                // }
                $message = array(true, 'Process Successful', 'Data updated successfully.', 'my_data_table.reload(\'#dt_project\')');
                echo json_encode($message);
            }else{
                $message = array(false, 'Process Fails', 'The data could not be updated.', '');
            }
            
        } else {
            $message = array(true, 'Process Successful', 'Data updated successfully.', '');
            echo json_encode($message);
        }
    }

    private function project_prefix() {
        if ($this->_DataIDProjectPrefix === null) {
            $project_prefixs = ProjectPrefixSrv::get();
            $getProject = array();
            $getProjectPrefix = array();
            foreach ($project_prefixs as $key => $val) {
                $getProject[$val->prefixNumber] = $val->projectID;
                $getProjectPrefix[$val->prefixNumber] = $val->prefixID;
                
            }
            $this->_DataIDProject = $getProject;
            $this->_DataIDProjectPrefix = $getProjectPrefix;
        }
        return 'succsess';
    }
    
    private function project_prefix_ip() {
        if ($this->_DataIDProjectIP === null) {
            $project_prefix_ips = ProjectPrefixIp::get();
            $getProjectIP = array();
            foreach ($project_prefix_ips as $key => $val) {
                $getProjectIP[$val->ipNumber] = $val->prefixIPID;
            }
            $this->_DataIDProjectIP = $getProjectIP;
        }
        return 'succsess';
    }
    
    private function dest_no_project_prefix_name() {
        if ($this->_DataDestNoPrefixName_premium === null) {
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
            
            $this->_DataDestNoPrefixName_premium = $getDestNoPrefixName_premium;
            $this->_DataDestNoPrefixName_pstn = $getDestNoPrefixName_pstn;
            $this->_DataDestNoPrefixName_all = $getDestNoPrefixName_all;
        }
        return 'succsess';
    }
    
    private function project_CustomerGroupMember() {
        if ($this->_DataCustomerGroupMember === null) {
            $CustomerPrices = CustomerGroupMember::get();
            $getCustomerPrice = array();
            foreach ($CustomerPrices as $key => $val) {
                $getCustomerPrice[$val->idxProject] = $val->idxCustomerGroup;
            }
            $this->_DataCustomerGroupMember = $getCustomerPrice;
        }
        return 'succsess';
    }

    public function project_price(){
        if ($this->_DataCustomerPrice === null) {
            $ProjectPrices = ProjectPrice::get();
            $getDataProjectPrice = array();
            foreach ($ProjectPrices as $key => $val) {
                $getDataProjectPrice[$val->projectID]['pricePSTN'] = $val->pricePSTN;
                $getDataProjectPrice[$val->projectID]['priceMobile'] = $val->priceMobile;
                $getDataProjectPrice[$val->projectID]['pricePremium'] = $val->pricePremium;
                $getDataProjectPrice[$val->projectID]['priceMinComm'] = $val->price;
                $getDataProjectPrice[$val->projectID]['bcPSTN'] = $val->bcPSTN;
                $getDataProjectPrice[$val->projectID]['bcMobile'] = $val->bcMobile;
                $getDataProjectPrice[$val->projectID]['bcPremium'] = $val->bcPremium;
            }

            $this->_DataProjectPrice = $getDataProjectPrice;
        }
        return 'succsess';
        
    }

    public function project_CustomerPrice(){
        if ($this->_DataCustomerPrice === null) {
            $CustomerPrices = CustomerPrice::get();
            $getDataCustomerPrice = array();
            foreach ($CustomerPrices as $key => $val) {
                $getDataCustomerPrice[$val->projectID][$val->prefixName] = $val->tarifPerMenit;
            }
            $this->_DataCustomerPrice = $getDataCustomerPrice;
        }
        return 'succsess';
        
    }

    public function Calculate($data_result, $setting_parsers)
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
                
                if (isset($call_data_server['destNo']) && strlen($call_data_server['destNo']) >= 4) {

                    // sourceIPOnly
                    $sourceIPOnly = $call_data_server['sourceIPOnly'];

                    // destNoCustPrefix
                    $destNoCustPrefix = substr($call_data_server['destNo'], 0, 4);

                    // destNoPrefix
                    $destNoPrefix = substr($call_data_server['destNo'], 4, 4);
                    $destNoPrefix = '62' . substr($destNoPrefix, 1);
                    
                    // destNoCust
                    $destNoCust = substr($call_data_server['destNo'], 4);
                    $destNoCust = '62' . substr($destNoCust, 1);

                    
                    $database_dataCDR = [
                        'destNoCustPrefix' => $destNoCustPrefix,
                        'destNoCust' => $destNoCust,
                        'destNoPrefix' => $destNoPrefix
                    ];
                    
                    $switch_idxCustomer = '';
                    switch (true) {
                        case array_key_exists($destNoCustPrefix, $this->_DataIDProject):
                            $switch_idxCustomer = $this->_DataIDProject[$destNoCustPrefix];
                            $switchArray_idxCustomer = [
                                'idxCustomer' => $switch_idxCustomer
                            ];
                            $database_dataCDR = array_merge($database_dataCDR, $switchArray_idxCustomer);
                        case array_key_exists($destNoCustPrefix, $this->_DataIDProjectPrefix):
                            $switch_idxCustomerIPPrefix = $this->_DataIDProjectPrefix[$destNoCustPrefix];
                            $switchArray_idxCustomerIPPrefix = [
                                'idxCustomerIPPrefix' => $switch_idxCustomerIPPrefix
                            ];
                            $database_dataCDR = array_merge($database_dataCDR, $switchArray_idxCustomerIPPrefix);
                        case array_key_exists($sourceIPOnly, $this->_DataIDProjectIP):
                            $switch_idxCustomerIP = $this->_DataIDProjectIP[$sourceIPOnly];
                            $switchArray_idxCustomerIP = [
                                'idxCustomerIP' => $switch_idxCustomerIP
                            ];
                            $database_dataCDR = array_merge($database_dataCDR, $switchArray_idxCustomerIP);
                    }
                    
                    // destnoprefixName || change destNoPrefix
                    $get_data_destNoPrefix_DestNoPrefixName = $this->get_data_destNoPrefix_DestNoPrefixName($destNoPrefix);
                    if (isset($get_data_destNoPrefix_DestNoPrefixName) && is_array($get_data_destNoPrefix_DestNoPrefixName) && !empty($get_data_destNoPrefix_DestNoPrefixName)) {
                        $database_dataCDR = array_merge($database_dataCDR, $get_data_destNoPrefix_DestNoPrefixName);
                    }
                    
                    // custPrice || custTime
                    $get_data_custPrice_custTime = $this->get_data_custPrice_custTime($switch_idxCustomer, $database_dataCDR['DestNoPrefixName'], $call_data_server['elapsedTime']);
                    if (isset($get_data_custPrice_custTime) && is_array($get_data_custPrice_custTime) && !empty($get_data_custPrice_custTime)) {
                        $database_dataCDR = array_merge($database_dataCDR, $get_data_custPrice_custTime);
                    }
                    
                    if (isset($database_dataCDR) && is_array($database_dataCDR) && !empty($database_dataCDR)) {
                        $ready_push = array_merge($coredata, $call_data_server, $database_dataCDR);
                        $dataToInsertCDR[] = $ready_push;
                    } else {
                        // Jika $destNoCustPrefix tidak ada dalam $_prefixDataProject
                        $ready_push = array_merge($coredata, $call_data_server);
                        $dataToInsertRepairCSV[] = $ready_push;
                    }

                }
            } else {
                $dataToInsertRepairCSV[] = $coredata;
            }
        }
        
        $data['dataToInsertCDR'] = $dataToInsertCDR;
        $data['dataToInsertRepairCSV'] = $dataToInsertRepairCSV;
        // $data['dataToInsertRepairCDR'] = $dataToInsertRepairCDR;
        // print_r ($data);exit;

        return $data;
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
    
    get_data_custPrice_custTime($idxProject, $DestNoPrefixName, $elapsedTime)
    private function get_data_custPrice($idxProject, $DestNoPrefixName, $elapsedTime){
        
        $custTimeData = $elapsedTime;
        $custPrice = 0;
        switch (true) {
            case stripos($DestNoPrefixName, "Premium") !== false:
                $pricePremium = $penghitung_array['pricePremium'];
                $bcPremium = $penghitung_array['bcPremium'];
                $ceilCustTimeData = ceil($custTimeData % 60);
                $PricecustTime = $ceilCustTimeData * $pricePremium;

                $data_return = [
                    'custPrice' => $PricecustTime,
                    'custTime' => $ceilCustTimeData*60,
                    'reasonCode' => "Premium data harga di bulatkan ke atas"

                ];
                break;
            case stripos($DestNoPrefixName, "PSTN") !== false:
                $pricePSTN = $penghitung_array['pricePSTN'];
                $bcPSTN = $penghitung_array['bcPSTN'];
                $custPrice = $pricePSTN * $custTimeData;
                $data_return = [
                    'custPrice' => $custPrice,
                    'custTime' => $custTimeData,
                    'reasonCode' => ""

                ];
                break;
            default:
                $priceMobile = $penghitung_array['priceMobile'];
                $bcMobile = $penghitung_array['bcMobile'];
                $custPrice = $priceMobile * $custTimeData;
                $data_return = [
                    'custPrice' => $custPrice,
                    'custTime' => $custTimeData,
                    'reasonCode' => ""

                ];
            
        }



































        // CAST(custtime AS DECIMAL(10, 2)) / 60 * CASE WHEN 0 = 1 AND customer_frees.idx IS NOT NULL THEN 0 ELSE COALESCE(customer_group_prices.tarifPerMenit, Custprice) END AS TotalPrice

        // switch (true) {
        //     case array_key_exists($idxProject, $this->_DataProjectPrice):
        //         $IdGroupMember = $this->_DataCustomerGroupMember[$idxProject];

        //         // ntar di hitung custPrice dan juga custTime
        //         $data_return = [
        //             'custPrice' => 99,
        //             'custTime' => $elapsedTime,
        //             'reasonCode' => "data project merupakan bagian dari customer group : $IdGroupMember"

        //         ];
        //         break;
        //     case array_key_exists($idxProject, $this->_DataCustomerGroupMember):
        //         $IdGroupMember = $this->_DataCustomerGroupMember[$idxProject];

        //         // ntar di hitung custPrice dan juga custTime
        //         $data_return = [
        //             'custPrice' => 99,
        //             'custTime' => $elapsedTime,
        //             'reasonCode' => "data project merupakan bagian dari customer group : $IdGroupMember"

        //         ];
        //         break;
            
        // }

        // return $data_return;
        // foreach ($awalan_array as $value) {
        //     if (preg_match($patterns, $value)) {
        //         echo "ada data string di dalam";
        //         break;
        //     }
        // }
        
    }

    private function get_data_custPrice_custTime($idxProject, $DestNoPrefixName, $elapsedTime) {
        echo $idxProject;exit;
        
        switch (true) {
            case array_key_exists($idxProject, $this->_DataCustomerGroupMember):
                $IdGroupMember = $this->_DataCustomerGroupMember[$idxProject];

                // ntar di hitung custPrice dan juga custTime
                $data_return = [
                    'custPrice' => 99,
                    'custTime' => $elapsedTime,
                    'reasonCode' => "data project merupakan bagian dari customer group : $IdGroupMember"

                ];
                break;
            case array_key_exists($idxProject, $this->_DataProjectPrice):
                $penghitung_array = $this->_DataProjectPrice[$idxProject];

                $custTimeData = $elapsedTime;
                $custPrice = 0;
                switch (true) {
                    case stripos($DestNoPrefixName, "Premium") !== false:
                        $pricePremium = $penghitung_array['pricePremium'];
                        $bcPremium = $penghitung_array['bcPremium'];
                        $ceilCustTimeData = ceil($custTimeData % 60);
                        $PricecustTime = $ceilCustTimeData * $pricePremium;

                        $data_return = [
                            'custPrice' => $PricecustTime,
                            'custTime' => $ceilCustTimeData*60,
                            'reasonCode' => "Premium data harga di bulatkan ke atas"

                        ];
                        break;
                    case stripos($DestNoPrefixName, "PSTN") !== false:
                        $pricePSTN = $penghitung_array['pricePSTN'];
                        $bcPSTN = $penghitung_array['bcPSTN'];
                        $custPrice = $pricePSTN * $custTimeData;
                        $data_return = [
                            'custPrice' => $custPrice,
                            'custTime' => $custTimeData,
                            'reasonCode' => ""

                        ];
                        break;
                    default:
                        $priceMobile = $penghitung_array['priceMobile'];
                        $bcMobile = $penghitung_array['bcMobile'];
                        $custPrice = $priceMobile * $custTimeData;
                        $data_return = [
                            'custPrice' => $custPrice,
                            'custTime' => $custTimeData,
                            'reasonCode' => ""

                        ];
                    
                }
                
                break;
                
            case array_key_exists($idxProject, $this->_DataCustomerPrice):
                $penghitung_array = $this->_DataCustomerPrice[$idxProject];

                // ntar di hitung custPrice dan juga custTime
                $data_return = [
                    'custPrice' => 99,
                    'custTime' => $elapsedTime,
                    'reasonCode' => ""

                ];
                break;
                

            case stripos($DestNoPrefixName, "Premium") !== false:
                // ntar di hitung custPrice dan juga custTime
                $data_return = [
                    'custPrice' => 88,
                    'custTime' => 88,
                    'reasonCode' => "data merupakan DestNoPrefixName : Premium"
                ];
                $data_return = $this->get_data_custPrice('Premium', '');
                break;
            default:
                // ntar di hitung custPrice dan juga custTime
                $data_return = [
                    'custPrice' => 0,
                    'custTime' => $elapsedTime,
                    'reasonCode' => "elapsedTime harusnya sama dengan custTime"
                ];
                $data_return = $this->get_data_custPrice('', $elapsedTime);
            
        }

        return $data_return;
    }

    private function get_data_destNoPrefix_DestNoPrefixName($destNoPrefix) {
        
        // 1. baca dulu sistem Customer Group ini  untuk masalah harga
        // $awalan_array_premium = $this->_DataDestNoPrefixName_premium;
        // $patterns_premium = '/^(' . implode('|', array_map('preg_quote', $awalan_array_premium)) . ')/';
        // $awalan_array_pstn = $this->_DataDestNoPrefixName_pstn;
        // $patterns_pstn = '/^(' . implode('|', array_map('preg_quote', $awalan_array_pstn)) . ')/';

        // print_r($this->_DataDestNoPrefixName_all);exit;
        // $this->_DataDestNoPrefixName_pstn = $getDestNoPrefixName_pstn;
        // $this->_DataDestNoPrefixName_all = $getDestNoPrefixName_all;

        // ambil 4 angka awal
        $change_destNoPrefix5 = $destNoPrefix;
        // ambil 4 angka awal
        $change_destNoPrefix4 = substr($destNoPrefix, 0, 4);
        // ambil 3 angka awal
        $change_destNoPrefix3 = substr($destNoPrefix, 0, 3);
        
        // echo $change_destNoPrefix5;
        // echo "----";
        // echo $change_destNoPrefix4;
        // echo "----";
        // echo $change_destNoPrefix3;
        // echo "----";
        // exit;
        $data_return = [
            'destNoPrefix' => $destNoPrefix,
            'DestNoPrefixName' => 'kosong'
        ];
        $DestNoPrefixName = '';
        switch (true) {
            case array_key_exists($change_destNoPrefix5, $this->_DataDestNoPrefixName_all):
                $DestNoPrefixName = $this->_DataDestNoPrefixName_all[$change_destNoPrefix5];
                $data_return = [
                    'destNoPrefix' => $change_destNoPrefix5,
                    'DestNoPrefixName' => $DestNoPrefixName
                ];
            break;

            case array_key_exists($change_destNoPrefix4, $this->_DataDestNoPrefixName_all):
                $DestNoPrefixName = $this->_DataDestNoPrefixName_all[$change_destNoPrefix4];
                $data_return = [
                    'destNoPrefix' => $change_destNoPrefix4,
                    'DestNoPrefixName' => $DestNoPrefixName
                ];
            break;

            case array_key_exists($change_destNoPrefix3, $this->_DataDestNoPrefixName_pstn):
                $DestNoPrefixName = $this->_DataDestNoPrefixName_all[$change_destNoPrefix3];
                $data_return = [
                    'destNoPrefix' => $change_destNoPrefix3,
                    'DestNoPrefixName' => $DestNoPrefixName
                ];
            break;
            
            case array_key_exists($change_destNoPrefix3, $this->_DataDestNoPrefixName_all):
                $DestNoPrefixName = $this->_DataDestNoPrefixName_all[$change_destNoPrefix3];
                $data_return = [
                    'destNoPrefix' => $change_destNoPrefix3,
                    'DestNoPrefixName' => $DestNoPrefixName
                ];
            break;


            // case array_key_exists($change_destNoPrefix, $this->_DataDestNoPrefixName_premium):
            //     $DestNoPrefixName = $this->_DataDestNoPrefixName_premium[$change_destNoPrefix];
            //     $data_return = [
            //         'destNoPrefix' => $change_destNoPrefix,
            //         'DestNoPrefixName' => $DestNoPrefixName
            //     ];
            //     break;

            // case array_key_exists($change_destNoPrefix, $this->_DataDestNoPrefixName_pstn):
            //     $DestNoPrefixName = $this->_DataDestNoPrefixName_pstn[$change_destNoPrefix];
            //     $data_return = [
            //         'destNoPrefix' => $destNoPrefix,
            //         'DestNoPrefixName' => $DestNoPrefixName
            //     ];
            // break;
            
            // case array_key_exists($change_destNoPrefix, $this->_DataDestNoPrefixName_all):
            //     $DestNoPrefixName = $this->_DataDestNoPrefixName_all[$change_destNoPrefix];
            //     $data_return = [
            //         'destNoPrefix' => $change_destNoPrefix,
            //         'DestNoPrefixName' => $DestNoPrefixName
            //     ];
            //     break;
            
        }
    
        return $data_return;
            
    }

}

