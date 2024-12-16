<?php

namespace App\Http\Controllers\feature;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Services\MyService;
use App\Services\CalculateService;
use App\Models\cdr;

use App\Models\monitoring\ParserCsv;
use App\Models\monitoring\RepairCsv;
use App\Models\monitoring\SettingParser;
use App\Models\Prefix;
use App\Models\CustomerIp;
use App\Models\CustomerPrice;
use App\Models\CustomerIpPrefix;
use App\Models\ProjectPrefixSrv;
use App\Models\Supplier;
use App\Models\SupplierIp;
use App\Models\SupplierIpPrefix;
use App\Models\SupplierPrice;

class CalculateController extends Controller
{

    private $_page_title = 'Calculate CDR';
    private $_url_data = 'reportcdr';
    private $_myService;
    private $_access_menu;

    private $_CalculateService;

    private $_DataIDProject = null;
    private $_DataDestNoPrefixName_all = null;
    private $_DataCustomerPrice = null;

    private $_DataCustomerIp = null;
    private $_DataCustomerIpPrefix = null;
    private $_DataSourceIpFixed = null;
    private $_DataSourceIpValue = null;
    
    private $_DataIDSupplier = null;
    private $_DataSupplierIp = null;
    private $_DataSupplierPrice = null;
    private $_DataSupplierIpFixed = null;
    private $_DataSupplierIpValue = null;
    private $_DataSupplierPrefixByIdxSupplier = Null;
    private $_DataSupplierPrefixByPrefix = Null;
    private $_DataSupplierPrefixByIdxSupplierPrefix = Null;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_myService = app(MyService::class);
        $this->_CalculateService = app(CalculateService::class);
        $this->get_customer_ip_by_prefix();
        $this->get_prefix_name_by_prefixNumber();
        $this->get_project_CustomerPrice();
        $this->get_project_CustomerIp();
        $this->get_SupplierIp();
        $this->get_Supplier();
        $this->get_supplier_ip_by_prefix();
        $this->getSupplierPrice();
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->_access_menu = $this->_myService->parser_access_menu($this->_url_data);
            return $next($request);
        });
    }
    

    public function get_prefix_name_by_prefixNumber() {
        $prefixes = Prefix::get();
        $getDestNoPrefixName_all = array();
        foreach ($prefixes as $key => $val) {
            $getDestNoPrefixName_all[$val->prefixNumber] = $val->prefixName;
        }
        $this->_DataDestNoPrefixName_all = $getDestNoPrefixName_all;
        
        return 'succsess';
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
        $data_result = ParserCsv::limit(1)->get();
        $setting_parsers = SettingParser::with('setting_parser_regex', 'setting_parser_row')->get();
        $updates = [];
        
        if (!$data_result->isEmpty() && $data_result->count() >= 1 && !$setting_parsers->isEmpty()) {
            $dataToInsert = $this->Calculate($data_result, $setting_parsers);
            echo 'ada callback dari Calculate';exit;
            if (Cdr::insert($dataToInsert['dataToInsertCDR']) && repaircsv::insert($dataToInsert['dataToInsertRepairCSV'])) {                               
                if (ParserCsv::limit(1)->delete()) {
                    // $this->update_cdr();
                }
                $message = array(true, 'Process Successful', 'Data updated successfully.', 'my_data_table.reload(\'#dt_project\')');
            }else{
                $message = array(false, 'Process Fails', 'The data could not be updated.', '');
            }
            
        } else {
            $message = array(true, 'Process Successful', 'Data updated successfully.', '');
            echo json_encode($message);
        }
    }
    
    public function Calculate($data_result, $setting_parsers)
    {
        $dataToInsertCDR = array();
        $dataToInsertRepairCSV = array();
        $dataToInsertRepairCDR = array();
        foreach ($data_result as $value) {
            $parts1 = explode('\\', $value->ReadyPath);
            
            $result1 = isset($parts1[count($parts1) - 2]) ? $parts1[count($parts1) - 2] : "Format path tidak sesuai pada path 1.";

            
            $setting_parser = array();
            $dataCallback = array();

            $idx = $value->idx;
            $valuesArray = str_getcsv($value->data);
            $jmlKolPHP = count($valuesArray);
            
            switch (true) {
                case preg_match("/\b.*MERA.*\b/i", $result1):
                    $dataServer = 'MERA';
                    $setting_parser = $setting_parsers[0];
                    if ($setting_parser->teknik_parser == 'column'){
                        $call_data_server = $this->_CalculateService->readMERA($valuesArray, $setting_parser);
                        
                    } else {
                        $call_data_server = $this->_CalculateService->readMERA($value->data, $setting_parser);
                    }
                    break;
                 case preg_match("/\b.*VOS.*\b/i", $result1):
                    $dataServer = 'VOS';
                    $setting_parser = $setting_parsers[1];
                    $call_data_server = $this->_CalculateService->readVOS($valuesArray, $setting_parser);
                    break;
                case preg_match("/\b.*ELAS.*\b/i", $result1):
                    $dataServer = 'ELASTIX';
                    $setting_parser = $setting_parsers[2];
                    $call_data_server = $this->_CalculateService->readEDirect($valuesArray, $setting_parser);
                    break;
                case preg_match("/\b.*ASTER.*\b/i", $result1):
                    $dataServer = 'ASTERISK';
                    $setting_parser = $setting_parsers[2];
                    $call_data_server = $this->_CalculateService->readEDirect($valuesArray, $setting_parser);
                    break;
                default:
                    $dataServer = 'Direct'; // ini data server ELASTIX dan ASTERISK
                    $setting_parser = $setting_parsers[2];
                    $call_data_server = $this->_CalculateService->readEDirect($valuesArray, $setting_parser);
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

            $checkNODID = $call_data_server['sourceNo'];
            if (isset($call_data_server['destNo']) && strlen($call_data_server['destNo']) >= 4 && !preg_match("/\b.*NODID.*\b/i", $checkNODID)) {
                $data_default_result = [];
                $data_default_result = [
                    'datetime' => '',
                    'sourceNo' => '',
                    'sourceNoOut' => '',
                    'sourceIP' => '',
                    'elapsedTime' => '',
                    'destNo' => '',
                    'destNoOut' => '',
                    'destIP' => '',
                    'destName' => '',
                    'sourceIPValue' => Null,
                    'destIPValue' => Null,
                    'sourceIPFixed' => Null,
                    'destIPFixed' => Null,
                    'idxCustomer' => '',
                    'idxCustomerIP' => Null,
                    'idxCustomerIPPrefix' => Null,
                    'destNoCustPrefix' => '',
                    'destNoPrefix' => '',
                    'destNoCust' => '',
                    'destNoPrefixName' => '',
                    'idxSupplier' => Null,
                    'idxSupplierIP' => Null,
                    'idxSupplierIPPrefix' => Null,
                    'destNoSuppPrefix' => Null,
                    'destNoSupplier' => Null,
                    'destNoSupplierPrefix' => Null,
                    'destNoSupplierPrefixName' => Null,
                    'destNoRealPrefix' => '',
                    'destNoRealPrefixName' => '',
                    'custTime' => '',
                    'custPrice' => '',
                    'supplierPrice' => Null,
                    'supplierTime' => Null,
                    'idxServer' => Null,
                    'reasonCode' => '' 
                ];

                // destNoCust
                $destNoCust = substr($call_data_server['destNo'], 4);
                $tempdestNoCust = '62' . substr($destNoCust, 1);
                $_tempdestNoCust = [
                    'destNoCust' => $tempdestNoCust
                ];
                $data_default_result = array_merge($data_default_result, $_tempdestNoCust);

                // destNoCustPrefix
                $tempdestNoCustPrefix = substr($call_data_server['destNo'], 0, 4);
                $_tempdestNoCustPrefix = [
                    'destNoCustPrefix' => $tempdestNoCustPrefix
                ];
                $data_default_result = array_merge($data_default_result, $_tempdestNoCustPrefix);
                
                // idxCustomer
                $tempIdxCustomer = '';
                $tempSourceIPValue = Null;
                $tempSourceIPFixed = Null;
                $tempCustomerIP = Null;
                $tempCustomerIpFixed = Null;
                if (isset($this->_DataIDProject[$tempdestNoCustPrefix])) {
                    
                    $tempIdxCustomer = $this->_DataIDProject[$tempdestNoCustPrefix];

                    // sourceIPValue
                    if (isset($this->_DataSourceIpValue[$tempIdxCustomer][$call_data_server['sourceIPOnly']])) {
                        $tempSourceIPValue = $this->_DataSourceIpValue[$tempIdxCustomer][$call_data_server['sourceIPOnly']];
                    }

                    // sourceIPFixed
                    if (isset($this->_DataSourceIpFixed[$tempIdxCustomer][$call_data_server['sourceIPOnly']])) {
                        $tempSourceIPFixed = $this->_DataSourceIpFixed[$tempIdxCustomer][$call_data_server['sourceIPOnly']];
                    }
                    
                    // idxCustomerIP
                    if (isset($this->_DataCustomerIp[$tempIdxCustomer][$call_data_server['sourceIPOnly']])) {
                        $tempCustomerIP = $this->_DataCustomerIp[$tempIdxCustomer][$call_data_server['sourceIPOnly']];                 
                    }

                    // idxCustomerIPPrefix
                    if (isset($this->_DataCustomerIpPrefix[$tempdestNoCustPrefix])) {
                        $tempCustomerIpFixed = $this->_DataCustomerIpPrefix[$tempdestNoCustPrefix];
                    }

                    $_tempIdxCustomer = [
                        'idxCustomer' => $tempIdxCustomer,
                        'idxCustomerIP' => $tempCustomerIP,
                        'idxCustomerIPPrefix' => $tempCustomerIpFixed,
                        'sourceIPFixed' => $tempSourceIPFixed,
                        'sourceIPValue' => $tempSourceIPValue
                    ];
                    $data_default_result = array_merge($data_default_result, $_tempIdxCustomer);
                }
                
                
                $tempIdxsupplier = Null;
                $tempidxSupplierIP = Null;
                $tempidxSupplierIPPrefix = Null;
                $tempDestIPValue = Null;
                $tempDestIPFixed = Null;
                // print_r ($call_data_server); exit;
                if ($tempIdxsupplier = $this->getDataSupp($call_data_server)){
                    
                    // destIPValue
                    if (isset($this->_DataSupplierIpValue[$tempIdxsupplier][$call_data_server['destIPOnly']])) {
                        $tempDestIPValue = $this->_DataSupplierIpValue[$tempIdxsupplier][$call_data_server['destIPOnly']];
                    }

                    // destIPFixed
                    if (isset($this->_DataSupplierIpFixed[$tempIdxsupplier][$call_data_server['destIPOnly']])) {
                        $tempDestIPFixed = $this->_DataSupplierIpFixed[$tempIdxsupplier][$call_data_server['destIPOnly']];
                    }

                    // idxSupplierIP
                    if (isset($this->_DataSupplierIp[$tempIdxsupplier][$call_data_server['destIPOnly']])) {
                        $tempidxSupplierIP = $this->_DataSupplierIp[$tempIdxsupplier][$call_data_server['destIPOnly']];
                    }
                    
                    // idxSupplierIPPrefix
                    if (isset($this->_DataSupplierPrefixByIdxSupplierPrefix[$tempIdxsupplier][$call_data_server['destIPOnly']])) {
                        $tempidxSupplierIPPrefix = $this->_DataSupplierPrefixByIdxSupplierPrefix[$tempIdxsupplier][$call_data_server['destIPOnly']];
                    }else{
                        if (isset($this->_DataSupplierPrefixByIdxSupplier[$tempIdxsupplier])) {
                            $tempidxSupplierIPPrefix = $this->_DataSupplierPrefixByIdxSupplier[$tempIdxsupplier];
                        }else{
                            if (isset($this->_DataSupplierPrefixByPrefix[$tempIdxsupplier][$call_data_server['destIPOnly']])) {
                                $tempidxSupplierIPPrefix = $this->_DataSupplierPrefixByPrefix[$tempIdxsupplier][$call_data_server['destIPOnly']];
                            }
                        }

                    }
                    
                    $_tempSourceIP = [
                        'idxSupplier' => $tempIdxsupplier,
                        'idxSupplierIP' => $tempidxSupplierIP,
                        'idxSupplierIPPrefix' => $tempidxSupplierIPPrefix,
                        'destIPValue' => $tempDestIPValue,
                        'destIPFixed' => $tempDestIPFixed
                    ];
                    $data_default_result = array_merge($data_default_result, $_tempSourceIP);
                }

                
                $data_default_result = array_merge($coredata, $data_default_result, $call_data_server);
                print_r ($data_default_result);exit;
                $data_default_result = $this->getDataDefaultResult($data_default_result, 4);
                switch (true) {
                    case preg_match("/\b.*MERA.*\b/i", $result1):
                        $data_default_result = $this->getDataSupplierDefaultMera($data_default_result, 4);
                        break;
                     case preg_match("/\b.*VOS.*\b/i", $result1):
                        $data_default_result = $this->getDataSupplierDefault($data_default_result, 4);
                        break;
                    case preg_match("/\b.*ELAS.*\b/i", $result1):
                        $data_default_result = $this->getDataSupplierDefault($data_default_result, 4);
                        break;
                    case preg_match("/\b.*ASTER.*\b/i", $result1):
                        $data_default_result = $this->getDataSupplierDefaultASTERISK($data_default_result, 4);
                        break;
                    default:
                    $data_default_result = $this->getDataSupplierDefault($data_default_result, 4);
                        break;
                }
                
                $dataToInsertCDR[] = $data_default_result;
            } else {
                $dataToInsertRepairCSV[] = $coredata;
            }

        }
        
        $data['dataToInsertCDR'] = $dataToInsertCDR;
        $data['dataToInsertRepairCSV'] = $dataToInsertRepairCSV;
        // $data['dataToInsertRepairCDR'] = $dataToInsertRepairCDR;
        // print_r ($data['dataToInsertCDR']);exit;

        return $data;
    }
    
    // informasi detil Supplier Default
    private function getDataSupplierDefault($data_default_result, $hitung = 0){
        
        $real_destNoOut = $data_default_result['destNoOut'];
        $real_idxSupplier = $data_default_result['idxSupplier'];

        $_temp_destNoSuppPrefix = substr($real_destNoOut, 0, 4);
        $_temp_destNoSupplierPrefix = substr($real_destNoOut, 4, $hitung);
        $_temp_destNoSupplier = substr($real_destNoOut, 4);
        $patterns = array("081", "021", "81");
        foreach ($patterns as $pattern) {
            if (strpos($real_destNoOut, $pattern) !== false) {
                $pieces = explode($pattern, $_temp_destNoSupplierPrefix);
                if (count($pieces) > 1) {
                    $_temp_destNoSuppPrefix = $_temp_destNoSuppPrefix . $pieces[0];
                    // echo '_temp_destNoSuppPrefix'.$_temp_destNoSuppPrefix. 'zz';
                    $_temp_destNoSupplier = substr($real_destNoOut, strlen($_temp_destNoSuppPrefix));
                    // echo '_temp_destNoSupplier'.$_temp_destNoSupplier. 'zz';
                    $_temp_destNoSupplierPrefix = substr($_temp_destNoSupplier, 0, $hitung);
                    // echo '_temp_destNoSupplierPrefix'.$_temp_destNoSupplierPrefix. 'zz';
                }                
                break;
            }
        }
        if (substr($_temp_destNoSupplierPrefix, 0, 1) === "0") {
            $_temp_destNoSupplierPrefix = "62" . substr($_temp_destNoSupplierPrefix, 1);
            $_temp_destNoSupplier = "62" . substr($_temp_destNoSupplier, 1);
        }else{
            // echo $_temp_destNoSupplierPrefix;exit;
            $_temp_destNoSupplierPrefix = "62" . $_temp_destNoSupplierPrefix;
            $_temp_destNoSupplier = "62" . $_temp_destNoSupplier;
        }
        
        $_temp_destNoSupplierPrefixName = Null;
        if (isset($this->_DataDestNoPrefixName_all[$_temp_destNoSupplierPrefix])) {
            $_temp_destNoSupplierPrefixName = $this->_DataDestNoPrefixName_all[$_temp_destNoSupplierPrefix];
            $_temp_supplierTime = $data_default_result['elapsedTime'];

            $_temp_supplierPrice = Null;
            if (isset($this->_DataSupplierPrice[$real_idxSupplier][$_temp_destNoSupplierPrefixName])) {
                $_temp_supplierPrice = $this->_DataSupplierPrice[$real_idxSupplier][$_temp_destNoSupplierPrefixName];
                
            }
            
            $BuatNgecekDulu = [
                'destNoSuppPrefix' => $_temp_destNoSuppPrefix,
                'destNoSupplier' => $_temp_destNoSupplier,
                'destNoSupplierPrefix' => $_temp_destNoSupplierPrefix,
                'destNoSupplierPrefixName' => $_temp_destNoSupplierPrefixName,
                'supplierPrice' => $_temp_supplierPrice,
                'supplierTime' => $_temp_supplierTime,
            ];
            $data_default_result = array_merge($data_default_result, $BuatNgecekDulu); 

        }else{
            return $this->getDataSupplierDefault($data_default_result, $hitung-1);
        }
        
        
        if (isset($BuatNgecekDulu)) {
            $data_default_result = array_merge($data_default_result, $BuatNgecekDulu); 
            return $data_default_result;
        }else{
            return $data_default_result;
        }
    }

    // informasi detil Supplier Default Mera
    private function getDataSupplierDefaultMera($data_default_result, $hitung = 0){
        
        $real_destNoOut = $data_default_result['destNoOut'];
        $real_idxSupplier = $data_default_result['idxSupplier'];

        $_temp_destNoSuppPrefix = substr($real_destNoOut, 0, 4);
        $_temp_destNoSupplierPrefix = substr($real_destNoOut, 4, $hitung);
        $_temp_destNoSupplier = substr($real_destNoOut, 4);
        $patterns = array("081", "021", "81");
        foreach ($patterns as $pattern) {
            if (strpos($real_destNoOut, $pattern) !== false) {
                $pieces = explode($pattern, $_temp_destNoSupplierPrefix);
                if (count($pieces) > 1) {
                    $_temp_destNoSuppPrefix = $_temp_destNoSuppPrefix . $pieces[0];
                    // echo '_temp_destNoSuppPrefix'.$_temp_destNoSuppPrefix. 'zz';
                    $_temp_destNoSupplier = substr($real_destNoOut, strlen($_temp_destNoSuppPrefix));
                    // echo '_temp_destNoSupplier'.$_temp_destNoSupplier. 'zz';
                    $_temp_destNoSupplierPrefix = substr($_temp_destNoSupplier, 0, $hitung);
                    // echo '_temp_destNoSupplierPrefix'.$_temp_destNoSupplierPrefix. 'zz';
                }                
                break;
            }
        }
        if (substr($_temp_destNoSupplierPrefix, 0, 1) === "0") {
            $_temp_destNoSupplierPrefix = "62" . substr($_temp_destNoSupplierPrefix, 1);
            $_temp_destNoSupplier = "62" . substr($_temp_destNoSupplier, 1);
        }else{
            // echo $_temp_destNoSupplierPrefix;exit;
            $_temp_destNoSupplierPrefix = "62" . $_temp_destNoSupplierPrefix;
            $_temp_destNoSupplier = "62" . $_temp_destNoSupplier;
        }
        
        $_temp_destNoSupplierPrefixName = Null;
        if (isset($this->_DataDestNoPrefixName_all[$_temp_destNoSupplierPrefix])) {
            $_temp_destNoSupplierPrefixName = $this->_DataDestNoPrefixName_all[$_temp_destNoSupplierPrefix];
            $_temp_supplierTime = $data_default_result['elapsedTime'];

            $_temp_supplierPrice = Null;
            if (isset($this->_DataSupplierPrice[$real_idxSupplier][$_temp_destNoSupplierPrefixName])) {
                $_temp_supplierPrice = $this->_DataSupplierPrice[$real_idxSupplier][$_temp_destNoSupplierPrefixName];
                
            }
            
            $BuatNgecekDulu = [
                'destNoSuppPrefix' => $_temp_destNoSuppPrefix,
                'destNoSupplier' => $_temp_destNoSupplier,
                'destNoSupplierPrefix' => $_temp_destNoSupplierPrefix,
                'destNoSupplierPrefixName' => $_temp_destNoSupplierPrefixName,
                'supplierPrice' => $_temp_supplierPrice,
                'supplierTime' => $_temp_supplierTime,
            ];
            $data_default_result = array_merge($data_default_result, $BuatNgecekDulu); 

        }else{
            return $this->getDataSupplierDefault($data_default_result, $hitung-1);
        }
        
        
        if (isset($BuatNgecekDulu)) {
            $data_default_result = array_merge($data_default_result, $BuatNgecekDulu); 
            return $data_default_result;
        }else{
            return $data_default_result;
        }
    }

    // informasi detil Supplier Default Mera 
    private function getDataSupplierDefaultASTERISK($data_default_result, $hitung = 0){
        
        $real_destNoOut = $data_default_result['destNoOut'];
        if(Str::startsWith($real_destNoOut, 'SIP/')) {
            // Ambil bagian dari string setelah tanda / terakhir
            $real_destNoOut = Str::afterLast($real_destNoOut, '/');
        }
        $real_idxSupplier = $data_default_result['idxSupplier'];

        $_temp_destNoSuppPrefix = substr($real_destNoOut, 0, 4);
        $_temp_destNoSupplierPrefix = substr($real_destNoOut, 4, $hitung);
        $_temp_destNoSupplier = substr($real_destNoOut, 4);
        $patterns = array("081", "021", "81");
        foreach ($patterns as $pattern) {
            if (strpos($real_destNoOut, $pattern) !== false) {
                $pieces = explode($pattern, $_temp_destNoSupplierPrefix);
                if (count($pieces) > 1) {
                    $_temp_destNoSuppPrefix = $_temp_destNoSuppPrefix . $pieces[0];
                    // echo '_temp_destNoSuppPrefix'.$_temp_destNoSuppPrefix. 'zz';
                    $_temp_destNoSupplier = substr($real_destNoOut, strlen($_temp_destNoSuppPrefix));
                    // echo '_temp_destNoSupplier'.$_temp_destNoSupplier. 'zz';
                    $_temp_destNoSupplierPrefix = substr($_temp_destNoSupplier, 0, $hitung);
                    // echo '_temp_destNoSupplierPrefix'.$_temp_destNoSupplierPrefix. 'zz';
                }                
                break;
            }
        }
        if (substr($_temp_destNoSupplierPrefix, 0, 1) === "0") {
            $_temp_destNoSupplierPrefix = "62" . substr($_temp_destNoSupplierPrefix, 1);
            $_temp_destNoSupplier = "62" . substr($_temp_destNoSupplier, 1);
        }else{
            // echo $_temp_destNoSupplierPrefix;exit;
            $_temp_destNoSupplierPrefix = "62" . $_temp_destNoSupplierPrefix;
            $_temp_destNoSupplier = "62" . $_temp_destNoSupplier;
        }
        
        $_temp_destNoSupplierPrefixName = Null;
        if (isset($this->_DataDestNoPrefixName_all[$_temp_destNoSupplierPrefix])) {
            
            $_temp_destNoSupplierPrefixName = $this->_DataDestNoPrefixName_all[$_temp_destNoSupplierPrefix];
            $_temp_supplierTime = $data_default_result['elapsedTime'];

            $_temp_supplierPrice = Null;
            if (isset($this->_DataSupplierPrice[$real_idxSupplier][$_temp_destNoSupplierPrefixName])) {
                $_temp_supplierPrice = $this->_DataSupplierPrice[$real_idxSupplier][$_temp_destNoSupplierPrefixName];
                
            }
            
            $BuatNgecekDulu = [
                'destNoSuppPrefix' => $_temp_destNoSuppPrefix,
                'destNoSupplier' => $_temp_destNoSupplier,
                'destNoSupplierPrefix' => $_temp_destNoSupplierPrefix,
                'destNoSupplierPrefixName' => $_temp_destNoSupplierPrefixName,
                'supplierPrice' => $_temp_supplierPrice,
                'supplierTime' => $_temp_supplierTime,
            ];
            // print_r($real_idxSupplier);
            // echo 'aa';
            // print_r($BuatNgecekDulu);exit;
            $data_default_result = array_merge($data_default_result, $BuatNgecekDulu); 

        }else{
            return $this->getDataSupplierDefault($data_default_result, $hitung-1);
        }
        
        
        if (isset($BuatNgecekDulu)) {
            $data_default_result = array_merge($data_default_result, $BuatNgecekDulu); 
            return $data_default_result;
        }else{
            return $data_default_result;
        }
    }
    
}

