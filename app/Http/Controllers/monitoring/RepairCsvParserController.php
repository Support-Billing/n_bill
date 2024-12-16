<?php

namespace App\Http\Controllers\monitoring;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Services\MyService;
// use App\Services\CalculateService;
use App\Services\CdrKolomService;

// use App\Models\Prefix;
use App\Models\Project;
use App\Models\ProjectPrefixSrv;
use App\Models\ProjectPrefixIp;

// use App\Models\CustomerIp;
// use App\Models\CustomerIpPrefix;

// harga
use App\Models\CustomerGroupPrice; // customer_group_prices
use App\Models\CustomerPrice; // customer_prices
use App\Models\ProjectPrice; // project_prices

use App\Models\monitoring\RepairCsv;

use App\Models\monitoring\SettingParser;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class RepairCsvParserController extends Controller
{
    
    private $_page_title = 'Repair CSV Parser';
    private $_url_data = 'repaircsvparser';
    private $_access_menu;
    private $_myService;
    private $_CdrKolomService;
    private $_DataSettingParser = null;
    private $_SettingParsersMera ;
    private $_SettingParsersVos ;
    private $_SettingParsersDirect ;

    // Project
    private $_DataProjectPrefixSrv = null;
    private $_DataProjectPrefixSrv_Cust = null;
    private $_DataProjectPrefixSrv_Cust_FDesktop = null;
    
    // Customer
    private $_DataIDProject = null;
    // private $_DataCustomerIp = null;
    private $_DataCustomerIpPrefix = null;
    private $_DataSourceIpFixed = null;
    private $_DataSourceIpValue = null;
    private $_DataDestNoPrefixName_all = null;
    
    private $_DataCustomerGroup = null;
    private $_DataCustomerGroupPrice = null;
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
        $this->_CdrKolomService = app(CdrKolomService::class);
        $this->getSettingParser();
        $this->middleware('auth');
        $this->getProjectPrefixSrv();
        $this->getProject(); // project detail
        $this->get_prefix_name_by_prefixNumber();
        $this->getCustomerGroupPrice(); // customer_group_prices
        $this->getCustomerPrice(); // customer_prices
        $this->getProjectPrice(); // project_prices
        $this->middleware(function ($request, $next) {
            $this->_access_menu = $this->_myService->parser_access_menu($this->_url_data);
            return $next($request);
        });
    }
    
    function parseCsvManual($data) {
        // Regex untuk menangkap setiap elemen CSV, termasuk elemen kosong dan tanda kutip ganda
        $pattern = '/"([^"]*(?:""[^"]*)*)"|([^,]+)/';
        preg_match_all($pattern, $data, $matches);
    
        $results = [];
        foreach ($matches[0] as $match) {
            // Bersihkan tanda kutip luar dan ganti tanda kutip ganda dengan satu tanda kutip
            $match = trim($match, ','); // Hapus koma di luar elemen
            $match = trim($match, '"'); // Hapus tanda kutip di luar elemen
            $match = str_replace('""', '"', $match); // Ganti tanda kutip ganda dengan tanda kutip tunggal
            $results[] = $match; // Tambahkan ke hasil
        }
    
        return $results;
    }

    function customCsvParser($data, $delimiter = ',', $enclosure = '"') {
        $rows = explode("\n", $data);
        $parsed = [];
        foreach ($rows as $row) {
            $parsed[] = str_getcsv($row, $delimiter, $enclosure);
        }
        return $parsed;
    }

    public function getSettingParser() {
        $SettingParsers = SettingParser::with('setting_parser_regex', 'setting_parser_row')->get();
        
        if ($SettingParsers->isNotEmpty()) {
            $this->SettingParsersMera = $SettingParsers[0];
            $this->SettingParsersVos = $SettingParsers[1];
            $this->SettingParsersDirect = $SettingParsers[2]; // ASTERISK / ELASTIX
        }
        
        return 'succsess';
    }

    public function get_customer_ip_by_prefix_GAGALDIGUNAKANATAUDIGANTI() {
    // public function get_customer_ip_by_prefix() {

        $project_prefixs = CustomerIpPrefix::whereNotNull('prefix')->orderByRaw('LENGTH(prefix) DESC')->get();
        
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

    public function getProject() {
        $Projects = Project::get();
        $getCustomerGroups = array();
        foreach ($Projects as $key => $val) {
            if(isset($val->idxCoreCustomerGroup) && $val->idxCoreCustomerGroup !== null) {
                $getCustomerGroups[$val->idxCore] = $val->idxCoreCustomerGroup;
            }
        }

        $this->_DataCustomerGroup = $getCustomerGroups;

        return 'succsess';
    }
    
    public function getCustomerGroupPrice() {
        $Projects = CustomerGroupPrice::get();
        $getCustomerGroup = array();
        foreach ($Projects as $key => $val) {
            if(isset($val->idxCoreCustGroup) && $val->idxCoreCustGroup !== null) {
                $getCustomerGroups[$val->idxCoreCustomerGroup] = $val->tarifPerMenit;
            }
        }
        $this->_DataCustomerGroupPrice = $getCustomerGroups;
        // print_r ($this->_DataCustomerGroupPrice);exit;
        return 'succsess';
    }

    public function getCustomerPrice() {
        $CustomerPrices = CustomerPrice::get();
        $getDataCustomerPrice = array();
        foreach ($CustomerPrices as $key => $val) {
            $getDataCustomerPrice[$val->idxCoreProjectFDesktop][$val->prefixName] = $val->tarifPerMenit;
        }
        $this->_DataCustomerPrice = $getDataCustomerPrice;
        // print_r ($this->_DataCustomerPrice);exit;
        
        return 'succsess';
    }

    public function getProjectPrice() {
        $ProjectPrices = ProjectPrice::get();
        $getDataCustomerPrice = array();
        foreach ($ProjectPrices as $key => $val) {
            $getDataProjectPrice[$val->idxCoreProject]['pricePSTN'] = $val->pricePSTN;
            $getDataProjectPrice[$val->idxCoreProject]['priceMobile'] = $val->priceMobile;
            $getDataProjectPrice[$val->idxCoreProject]['pricePremium'] = $val->pricePremium;
            $getDataProjectPrice[$val->idxCoreProject]['price'] = $val->price;
        }
        $this->_DataProjectPrice = $getDataProjectPrice;
        // print_r ($this->_DataCustomerPrice);exit;
        
        return 'succsess';
    }

    public function getProjectPrefixSrv()
    {
        $ProjectPrefixSrvs = ProjectPrefixSrv::whereNotNull('IdxCoreProject')->get();
        $getProject = array();
        foreach ($ProjectPrefixSrvs as $key => $val) {
            $getProject[$val->prefixNumber] = $val->relIdxCoreProj;
            $getPCustomerFdesktop[$val->prefixNumber] = $val->idxCustomerFDesktop;
            $getPCustomer[$val->prefix] = $val->idxCore;
            
        }
        $this->_DataProjectPrefixSrv = $getProject;
        $this->_DataProjectPrefixSrv_Cust_FDesktop = $getPCustomerFdesktop;
        $this->_DataProjectPrefixSrv_Cust = $getProject;
        // print_r ($this->_DataProjectPrefixSrv);exit;
        return 'succsess';
    }

    // public function get_project_CustomerIp() {
    //     $CustomerIps = CustomerIp::get();
    //     $getDataCustomerIp = array();
    //     $getDataCustomerIpValue = array();
    //     $getDataCustomerIpFixed = array();
    //     foreach ($CustomerIps as $key => $val) {
    //         $getDataCustomerIp[$val->idxCustomer][$val->startIPOnly] = $val->idx;
    //         $getDataCustomerIpValue[$val->idxCustomer][$val->startIPOnly] = $val->startIPValue;
    //         $getDataCustomerIpFixed[$val->idxCustomer][$val->startIPOnly] = $val->startIPFixed;
    //     }
    //     $this->_DataCustomerIp = $getDataCustomerIp;
    //     $this->_DataSourceIpValue = $getDataCustomerIpValue;
    //     $this->_DataSourceIpFixed = $getDataCustomerIpFixed;
        
    //     return 'succsess';
    // }

    public function get_prefix_name_by_prefixNumber() {
        $prefixes = ProjectPrefixSrv::get();
        $getDestNoPrefixName_all = array();
        foreach ($prefixes as $key => $val) {
            $getDestNoPrefixName_all[$val->prefixNumber] = $val->prefixName;
        }
        $this->_DataDestNoPrefixName_all = $getDestNoPrefixName_all;
        
        return 'succsess';
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            return view('monitoring.repaircsv.index', [
                'page_title' => $this->_page_title,
                'import_otoritas_modul' => $this->_access_menu->import_otoritas_modul
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }

    public function load(Request $request)
    {
        if($request->ajax())
        {

            # Data Table
            $limit = $request->get('length');
            $offset = $request->get('start');
            $draw = $request->get('draw');
            $extra_search = $request->get('extra_search');
            $data_cond = $this->_myService->seriliaze_decode($extra_search);

            # Condition
            $cond = array();

            if (!empty($data_cond['keyword'])) {
                $cond[] = ['name', 'LIKE', '%' . $data_cond['keyword'] . '%'];
            }

            $data_result = RepairCsv::where($cond)->offset($offset)->limit($limit)->get();
            $data_count = RepairCsv::where($cond)->count();


            $rows = array();
            $no = $offset;

            foreach ($data_result as $value) {
                $id = $value->idx;
                $id = Crypt::encryptString($id);
                $id = urlencode($id);

                $action = "<a href='javascript:void(0);' 
                    class='btn bg-color-orange btn-xs margin-right-5 text-white' 
                    id='mybutton-show-{$id}' 
                    data-breadcrumb='View' 
                    onclick='my_form.open(this.id)' 
                    data-module='repaircsvparser' 
                    data-url='repaircsvparser/{$id}/show_all' 
                    data-original-title='View' 
                    rel='tooltip'
                    data-placement='left'><i class='fa fa-eye'></i></a>";
                
                $no++;
                $rows[] = array(
                    $no,
                    $value->serverData,
                    $value->folderName,
                    $value->fileName,
                    $value->lineNumber,
                    $action
                );
            }

            $data = array(
                "draw" => $draw,
                "recordsTotal" => $data_count,
                "recordsFiltered" => $data_count,
                "data" => $rows
            );

            echo json_encode($data);
        }else{
            return redirect('./#dashboard');
        }
    }

    /* ***** Kebutuhan 9 kolom ***** */
    public function get_kolom(string $idcore)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            return view('monitoring.repaircsv.get_kolom', [
                'page_title' => $this->_page_title
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }
    
    public function reason_kolom()
    {
        # Condition
        $cond = array();
        // $cond[] = ['serverData', 'ELASTIX'];
        // $cond[] = ['reasonCode','<>', 'not'];
        // $data_result = RepairCsv::where($cond)
        //     ->where(function($query) {
        //         // $query->WhereNull('reasonCode');
        //         $query->whereNull('datetime');
        //             // ->orWhereNull('sourceNo')
        //             // ->orWhereNull('elapsedTime');
        //     })
        //     ->where(function($query2) {
        //         $query2->WhereNull('reasonCode');
        //     })
        //     ->limit(1000)
        //     // ->toSql();
        //     ->get();
        //     // dd($data_result);

            $data_result = RepairCsv::where($cond)
                ->where(function($query) {
                    // $query->whereNull('dateTime');
                    $query->whereNull('elapsedTime');
                    
                })
                ->where(function($query2) {
                    $query2->whereNull('reasonCode')
                           ->orWhere('reasonCode', '=', 0);
                })
                ->limit(1)
                    // ->toSql();
                    ->get();
                    // dd($data_result);

        if (!$data_result->isEmpty() && $data_result->count() >= 1 ) {
            
            foreach ($data_result as $value) {
                
                $setting_parser = array();
                $dataCallback = array();

                $idx = $value->idxCore;
                $valuesString = $value->data;
                // $valuesArray = str_getcsv($value->data);
                $valuesArray = $this->parseCsvManual($value->data);
                // print_r($valuesArray);
                // exit;
                $jmlKolPHP = count($valuesArray);
                
                switch ($value->serverData) {
                    case 'MERA':
                        $setting_parser = $this->SettingParsersMera;

                        // datetime
                        $datetime = $this->_CdrKolomService->datetimeMERA($valuesArray, $valuesString, $setting_parser);
                        // sourceNo
                        $sourceNo = $this->_CdrKolomService->generalKolomMERA($valuesArray, $valuesString, $setting_parser, 'sourceNo');
                        // sourceNoOut
                        $sourceNoOut = $this->_CdrKolomService->generalKolomMERA($valuesArray, $valuesString, $setting_parser, 'sourceNoOut');
                        // sourceIP
                        $sourceIP = $this->_CdrKolomService->generalKolomMERA($valuesArray, $valuesString, $setting_parser, 'sourceIP');
                        // elapsedTime
                        $elapsedTime = $this->_CdrKolomService->generalKolomMERA($valuesArray, $valuesString, $setting_parser, 'elapsedTime');
                        // destNo
                        $destNo = $this->_CdrKolomService->generalKolomMERA($valuesArray, $valuesString, $setting_parser, 'destNo');
                        // destNoOut
                        $destNoOut = $this->_CdrKolomService->generalKolomMERA($valuesArray, $valuesString, $setting_parser, 'destNoOut');
                        // destIP
                        $destIP = $this->_CdrKolomService->generalKolomMERA($valuesArray, $valuesString, $setting_parser, 'destIP');
                        // destName
                        $destName = $this->_CdrKolomService->generalKolomMERA($valuesArray, $valuesString, $setting_parser, 'destName');
                        // destIPOnly
                        $destIPOnly = $this->_CdrKolomService->generalKolomMERA($valuesArray, $valuesString, $setting_parser, 'destIPOnly');
                        // sourceIPOnly
                        $sourceIPOnly = $this->_CdrKolomService->generalKolomMERA($valuesArray, $valuesString, $setting_parser, 'sourceIPOnly');
                        
                        break;
                    case 'VOS':
                        $setting_parser = $this->SettingParsersVos;
                        
                        // datetime
                        $datetime = $this->_CdrKolomService->datetimeVOS($valuesArray, $setting_parser);
                        // sourceNo
                        // $sourceNo = $this->_CdrKolomService->sourceNoVOS($valuesArray, $setting_parser);
                        $sourceNo = $this->_CdrKolomService->generalKolomVOS($valuesArray, $setting_parser, 'sourceNo');
                        // sourceNoOut
                        $sourceNoOut = $this->_CdrKolomService->generalKolomVOS($valuesArray, $setting_parser, 'sourceNoOut');
                        // sourceIP
                        $sourceIP = $this->_CdrKolomService->generalKolomVOS($valuesArray, $setting_parser, 'sourceIP');
                        // elapsedTime
                        $elapsedTime = $this->_CdrKolomService->generalKolomVOS($valuesArray, $setting_parser, 'elapsedTime');
                        // destNo
                        $destNo = $this->_CdrKolomService->generalKolomVOS($valuesArray, $setting_parser, 'destNo');
                        // destNoOut
                        $destNoOut = $this->_CdrKolomService->generalKolomVOS($valuesArray, $setting_parser, 'destNoOut');
                        // destIP
                        $destIP = $this->_CdrKolomService->generalKolomVOS($valuesArray, $setting_parser, 'destIP');
                        // destName
                        $destName = $this->_CdrKolomService->generalKolomVOS($valuesArray, $setting_parser, 'destName');
                        // destIPOnly
                        $destIPOnly = $this->_CdrKolomService->generalKolomVOS($valuesArray, $setting_parser, 'destIPOnly');
                        // sourceIPOnly
                        $sourceIPOnly = $this->_CdrKolomService->generalKolomVOS($valuesArray, $setting_parser, 'sourceIPOnly');
                        
                        break;
                    case 'ELASTIX':
                        $setting_parser = $this->SettingParsersDirect;
                        
                        // datetime
                        $datetime = $this->_CdrKolomService->datetimeDirect($valuesArray, $setting_parser, 'datetime');
                        // sourceNo
                        $sourceNo = $this->_CdrKolomService->sourceNoDirect($valuesArray, $setting_parser, 'sourceNo');
                        // sourceNoOut
                        $sourceNoOut = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'sourceNoOut');
                        // sourceIP
                        $sourceIP = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'sourceIP');
                        // elapsedTime
                        $elapsedTime = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'elapsedTime');
                        // destNo
                        $destNo = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'destNo');
                        // destNoOut
                        $destNoOut = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'destNoOut');
                        // destIP
                        $destIP = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'destIP');
                        // destName
                        $destName = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'destName');
                        // destIPOnly
                        $destIPOnly = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'destIPOnly');
                        // sourceIPOnly
                        $sourceIPOnly = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'sourceIPOnly');
                        
                        break;
                    case 'ASTERISK':
                        $setting_parser = $this->SettingParsersDirect;

                        // datetime
                        $datetime = $this->_CdrKolomService->datetimeDirect($valuesArray, $setting_parser, 'datetime');
                        // sourceNo
                        $sourceNo = $this->_CdrKolomService->sourceNoDirect($valuesArray, $setting_parser, 'sourceNo');
                        // sourceNoOut
                        $sourceNoOut = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'sourceNoOut');
                        // sourceIP
                        $sourceIP = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'sourceIP');
                        // elapsedTime
                        $elapsedTime = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'elapsedTime');
                        // destNo
                        $destNo = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'destNo');
                        // destNoOut
                        $destNoOut = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'destNoOut');
                        // destIP
                        $destIP = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'destIP');
                        // destName
                        $destName = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'destName');
                        // destIPOnly
                        $destIPOnly = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'destIPOnly');
                        // sourceIPOnly
                        $sourceIPOnly = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'sourceIPOnly');
                        
                        break;
                }

                if (!is_numeric($elapsedTime)) {
                    $call_data_server = array(
                        'idxCore' => $idx,
                        'datetime' => $datetime,
                        'sourceNo' => $sourceNo,
                        'sourceNoOut' => $sourceNoOut,
                        'sourceIP' => $sourceIP,
                        'elapsedTime' => 0,
                        'destNo' => $destNo,
                        'destNoOut' => $destNoOut,
                        'destIP' => $destIP,
                        'destName' => $destName,
                        'destIPOnly' => $destIPOnly,
                        'sourceIPOnly' => $sourceIPOnly,
                        'reasonCode' => 'not'
                    );

                }else{
                    $call_data_server = array(
                        'idxCore' => $idx,
                        'datetime' => $datetime,
                        'sourceNo' => $sourceNo,
                        'sourceNoOut' => $sourceNoOut,
                        'sourceIP' => $sourceIP,
                        'elapsedTime' => $elapsedTime,
                        'destNo' => $destNo,
                        'destNoOut' => $destNoOut,
                        'destIP' => $destIP,
                        'destName' => $destName,
                        'destIPOnly' => $destIPOnly,
                        'sourceIPOnly' => $sourceIPOnly,
                        'reasonCode' => '9'
                    );

                }
                $dataToUpdate[] = $call_data_server;
                print_r($call_data_server);exit;
            }
            
            $result = RepairCsv::upsert($dataToUpdate, ['idx'], ['datetime', 'sourceNo','sourceNoOut','sourceIP','elapsedTime','destNo','destNoOut','destIP','destName', 'destIPOnly', 'sourceIPOnly']);
            if ($result > 0) {
                $message = array(true, 'Process Successful', 'Data updated successfully.', 'nextload(\'Yes\')');
            }else{
                $message = array(false, 'Process Fails', 'The data could not be updated.', '');
            }
            
        }else{
            $message = array(true, 'Process Successful', 'All Data updated successfully.', 'nextload(\'no\')');
        }
        echo json_encode($message);
    }
    /* ***** end 9 kolom ***** */

    /* ***** Kebutuhan Customer ***** */
    public function get_kolom_customer(string $idcore)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            return view('monitoring.repaircsv.get_kolom_customer', [
                'page_title' => $this->_page_title
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }
    
    public function update_csv_customer()
    {
        # Condition
        // $query = "SELECT * FROM `repaircsv` WHERE `elapsedTime` != '0' LIMIT 0,1";
        $query = "SELECT * FROM  `s_billmonitor`.`repaircsv` WHERE `destNo` is not null and `idxCoreCustomer` is null and `reasonCode` is null LIMIT 3000";
        // $query = "SELECT * FROM  `s_billmonitor`.`repaircsv` WHERE `destNo` is not null LIMIT 1";
        $data_result = DB::select($query);

        if (!empty($data_result)) {
            
            foreach ($data_result as $value) {
                
                $idx = $value->idx;
                // echo $idx;exit;
                $destNo = $value->destNo;
                
                $elapsedTime = $value->elapsedTime;
                $sourceIPOnly = $value->sourceIPOnly;

                // destNoCust
                $destNoCust = Null;
                $spliteDestNo = substr($destNo, 4);
                $checkDestNoCust = substr($destNo, 4, 1);
                if($checkDestNoCust == 0){
                    $destNoCust = '62' . substr($spliteDestNo, 1);
                }
                
                // chek 021
                // echo 'destNo'.$destNo;
                $chekNo021 = substr($destNo, 4, 3);
                // echo 'chekNo021'.$chekNo021;
                // exit;

                // destNoCustPrefix
                // semua di kurangi satu karena di rubah dari 0 menjadi 62
                $idxCustomerFDesktop = '';
                $idxCustomer = '';
                $take_data = array(4,5,3,2,'all');
                foreach ($take_data as $value) {

                    if ($value == 'all' && $chekNo021 == '021') {
                        $destNoCustPrefix = substr($destNo, 0, (int)$value);
                        if (isset($this->_DataProjectPrefixSrv[$destNoCust])) {
                            $idxCustomer = $this->_DataProjectPrefixSrv[$destNoCust];
                            break;
                        }
                    } else {
                        // echo $value;
                        $destNoCustPrefix = substr($destNo, 0, (int)$value);
                        // echo $destNoCustPrefix;exit;
                        if($checkDestNoCust == 0){
                            $destNoCust = '62' . substr($spliteDestNo, 1);
                        }
                        if (isset($this->_DataProjectPrefixSrv[$destNoCustPrefix])) {
                            $idxCustomer = $this->_DataProjectPrefixSrv[$destNoCustPrefix];
                            $idxCustomerFDesktop = $this->_DataProjectPrefixSrv_Cust_FDesktop[$destNoCustPrefix];
                            // echo $destNoCustPrefix .'value' . $value . 'ini ada';
                            // echo $idxCustomer. 'idxCustomer ini ada';
                            break;
                        }
                    }
                    
                }
                // exit;
                
                // sourceIPValue
                $sourceIPValue = Null;
                if (isset($this->_DataSourceIpValue[$idxCustomerFDesktop][$sourceIPOnly])) {
                    $sourceIPValue = $this->_DataSourceIpValue[$idxCustomerFDesktop][$sourceIPOnly];
                }

                // sourceIPFixed
                $sourceIPFixed = Null;
                if (isset($this->_DataSourceIpFixed[$idxCustomerFDesktop][$sourceIPOnly])) {
                    $sourceIPFixed = $this->_DataSourceIpFixed[$idxCustomerFDesktop][$sourceIPOnly];
                }

                // idxCustomerIP
                $idxCustomerIP = Null;
                if (isset($this->_DataCustomerIp[$idxCustomerFDesktop][$sourceIPOnly])) {
                    $idxCustomerIP = $this->_DataCustomerIp[$idxCustomerFDesktop][$sourceIPOnly];
                }

                // idxCustomerIPPrefix
                $idxCustomerIPPrefix = Null;
                if (isset($this->_DataProjectPrefixSrv_Cust[$destNoCustPrefix])) {
                    $idxCustomerIPPrefix = $this->_DataProjectPrefixSrv_Cust[$destNoCustPrefix];
                }
                
                // destNoPrefix dan DestNoPrefixName
                $take_data = array(5, 6, 4, 3, 2);
                $arraydestNoPrefix = [];
                foreach ($take_data as $value) {
                    $destNoPrefix = substr($destNo, 4, (int)$value);
                    $checkDestNoPrefix = substr($destNoPrefix, 0, 1);
                    if($checkDestNoPrefix == 0){
                        $destNoPrefix = '62' . substr($destNoPrefix, 1);
                    }
                    $destNoPrefixName = Null;
                    if (isset($this->_DataDestNoPrefixName_all[$destNoPrefix])) {
                        $destNoPrefixName = $this->_DataDestNoPrefixName_all[$destNoPrefix];
                    }
                    $arraydestNoPrefix[] = ['destNoPrefix' => $destNoPrefix, 'destNoPrefixName' => $destNoPrefixName];
                }
                
                // Fungsi untuk memfilter elemen-elemen array yang memiliki nilai pada kedua kunci yang ditentukan
                $filteredArraydestNoPrefix = array_filter($arraydestNoPrefix, function($item) {
                    return !empty($item['destNoPrefix']) && !empty($item['destNoPrefixName']);
                });
                foreach ($filteredArraydestNoPrefix as $key => $value) {
                    $destNoPrefix = $value['destNoPrefix'];
                    $destNoPrefixName = $value['destNoPrefixName'];
                    break;
                }
                
                $custPrice = 0;

                // Cek apakah idxCustomer ada dalam _DataCustomerGroup
                if (isset($this->_DataCustomerGroup[$idxCustomer])) {
                    $idxCustomerGroup = $this->_DataCustomerGroup[$idxCustomer];
                    
                    // Cek apakah idxCustomerGroup ada dalam _DataCustomerGroupPrice
                    if (isset($this->_DataCustomerGroupPrice[$idxCustomerGroup])) {
                        $custPrice = $this->_DataCustomerGroupPrice[$idxCustomerGroup];
                    }
                }
                
                // Jika custPrice masih 0, coba ambil harga langsung dari _DataCustomerPrice
                if ($custPrice == 0 && isset($this->_DataCustomerPrice[$idxCustomer][$destNoPrefixName])) {
                    $custPrice = $this->_DataCustomerPrice[$idxCustomer][$destNoPrefixName];
                }
                
                if ($custPrice == 0 && isset($this->_DataProjectPrice[$idxCustomer])) {

                    
                    if (preg_match("/\bpremium\b/i", $destNoPrefixName)) {
                        $custPrice = $this->_DataProjectPrice[$idxCustomer]['pricePremium'];
                    }
                    if ($chekNo021 == '021') {
                        $custPrice = $this->_DataProjectPrice[$idxCustomer]['pricePSTN'];
                    }
                    if ($chekNo021 == '021') {
                        $custPrice = $this->_DataProjectPrice[$idxCustomer]['pricePSTN'];
                    }
                    if ($custPrice == 0) {
                        $custPrice = $this->_DataProjectPrice[$idxCustomer]['priceMobile'];
                    }
                    // $getDataProjectPrice[$val->idxCoreProject]['price'] = $val->price;
                }
                
                $custTime = Null;
                if (!empty($elapsedTime)) {
                    $custTime = $elapsedTime;
                    if (preg_match("/\bpremium\b/i", $destNoPrefixName)) {
                        $hasil = $custTime / 60;
                        $hasil_bulat = round($hasil);
                        $roundCustTimeData = $hasil_bulat * 60;
                        $custTime = $roundCustTimeData;
                    }
                }   

                $call_data_server = array(
                    'idx' => $idx,
                    'destNoCust' => $destNoCust,
                    'destNoCustPrefix' => $destNoCustPrefix,
                    'idxCoreCustomer' => $idxCustomer,
                    'idxCustomerIP' => $idxCustomerIP,
                    'idxCoreCustomerIPPrefix' => $idxCustomerIPPrefix,
                    'sourceIPFixed' => $sourceIPFixed,
                    'sourceIPValue' => $sourceIPValue,
                    'destNoPrefix' => $destNoPrefix,
                    'destNoPrefixName' => $destNoPrefixName,
                    'custTime' => $custTime,
                    'custPrice' => $custPrice,
                    'reasonCode' => Null
                );
                    
                $dataToUpdate[] = $call_data_server;
                // print_r('<br />');
                // print_r($dataToUpdate);exit;
            }
            
            $result = RepairCsv::upsert($dataToUpdate, ['idx'], ['destNoCust', 
                'destNoCust', 'destNoCustPrefix','idxCoreCustomer', 'idxCustomerIP','idxCoreCustomerIPPrefix',
                'sourceIPFixed','sourceIPValue','destNoPrefix','destNoPrefixName','custTime','custPrice',
            'reasonCode']);
            if ($result > 0) {
                $message = array(true, 'Process Successful', 'Data updated successfully.', 'nextload(\'Yes\')');
            }else{
                
                $call_data_serverK2 = array(
                    'idx' => $idx,
                    'reasonCode' => 'data bemasalah'
                );
                $dataToUpdateK2[] = $call_data_serverK2;

                $result_ke2 = RepairCsv::upsert($dataToUpdateK2, ['idx'], ['destNoCust', 
                    'destNoCust', 'destNoCustPrefix','idxCoreCustomer', 'idxCustomerIP','idxCoreCustomerIPPrefix',
                    'sourceIPFixed','sourceIPValue','destNoPrefix','destNoPrefixName','custTime','custPrice',
                'reasonCode']);
                if ($result_ke2 > 0) {
                    $message = array(true, 'Process Successful', 'Data updated successfully loop ke 2.', 'nextload(\'Yes\')');
                }else{
                    
                    $message = array(false, 'Process Fails', 'The data could not be updated.', '');
                }
            }
            
            
        }else{
            $message = array(true, 'Process Successful', 'All Data updated successfully.', 'nextload(\'no\')');
        }
        echo json_encode($message);
    }
    /* ***** end Customer ***** */
    
    /* ***** Kebutuhan Suppiler ***** */
    public function get_kolom_supplier(string $idcore)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            return view('monitoring.repaircsv.get_kolom_supplier', [
                'page_title' => $this->_page_title
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }
    /* ***** End Suppiler ***** */

    /* ***** Kebutuhan move kolom ***** */
    public function readymovecdr(string $idcore)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            return view('monitoring.repaircdr.move_kolom', [
                'page_title' => $this->_page_title
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }

    
    public function movecdr()
    {
        
        // Kondisi untuk memilih data dari table a
        $condition = [ 
            // ['kolom_a', '=', 'nilai_a'], // contoh kondisi
            // ['kolom_b', '>', 100],       // contoh kondisi
            ['datetime', '!=', null],      // kondisi untuk datetime yang null
            // ['reasonCode', '!=', null],    // kondisi untuk reasonCode yang null
            // tambahkan kondisi lainnya sesuai kebutuhan
        ];

        // Jumlah baris yang ingin dipindahkan
        $limit = 5000;

        // Memindahkan data dari table a ke table b dengan kondisi dan limit
        $inserted = DB::table('cdr')->insertUsing(
            ['idxCore', 'from', 'idx',
                'foldername',
                'filename',
                'ReadyPath',
                'ResultPath',
                'DateTimeCSVtoDB',
                'LineNumber',
                'serverData',
                'jmlKol',
                'jmlKolPHP',
                'data',
                'datetime',
                'sourceNo',
                'sourceNoOut',
                'sourceIP',
                'elapsedTime',
                'destNo',
                'destNoOut',
                'destIP',
                'destName',
                'sourceIPValue',
                'destIPValue',
                'sourceIPFixed',
                'destIPFixed',
                'idxCustomer',
                'idxCustomerIP',
                'idxCustomerIPPrefix',
                'destNoCustPrefix',
                'destNoPrefix',
                'destNoCust',
                'destNoPrefixName',
                'idxSupplier',
                'idxSupplierIP',
                'idxSupplierIPPrefix',
                'destNoSuppPrefix',
                'destNoSupplier',
                'destNoSupplierPrefix',
                'destNoSupplierPrefixName',
                'destNoRealPrefix',
                'destNoRealPrefixName',
                'custTime',
                'custPrice',
                'supplierPrice',
                'supplierTime',
                'destIPOnly',
                'sourceIPOnly',
                'created',
                'modified',
                'idxServer',
                'reasonCode'
            ],
            function ($query) use ($condition, $limit) {
                $uuid = (string) Str::uuid();
                $query->select(DB::raw("UUID()"), DB::raw("'Repair CDR'"), 'idx',
                'foldername',
                'filename',
                'ReadyPath',
                'ResultPath',
                'DateTimeCSVtoDB',
                'LineNumber',
                'serverData',
                'jmlKol',
                'jmlKolPHP',
                'data',
                'datetime',
                'sourceNo',
                'sourceNoOut',
                'sourceIP',
                'elapsedTime',
                'destNo',
                'destNoOut',
                'destIP',
                'destName',
                'sourceIPValue',
                'destIPValue',
                'sourceIPFixed',
                'destIPFixed',
                'idxCustomer',
                'idxCustomerIP',
                'idxCustomerIPPrefix',
                'destNoCustPrefix',
                'destNoPrefix',
                'destNoCust',
                'destNoPrefixName',
                'idxSupplier',
                'idxSupplierIP',
                'idxSupplierIPPrefix',
                'destNoSuppPrefix',
                'destNoSupplier',
                'destNoSupplierPrefix',
                'destNoSupplierPrefixName',
                'destNoRealPrefix',
                'destNoRealPrefixName',
                'custTime',
                'custPrice',
                'supplierPrice',
                'supplierTime',
                'destIPOnly',
                'sourceIPOnly',
                'created',
                'modified',
                'idxServer',
                'reasonCode')
                    ->from('repaircdr')
                    ->where($condition)
                    ->orderBy('idx', 'asc') 
                    ->limit($limit);
            }
        );
        
        

        if ($inserted) {
            // Jika berhasil, hapus data dari table a berdasarkan kondisi dan limit yang sama
            $deleted = DB::table('repaircdr')->where($condition)->orderBy('idx', 'asc')->limit($limit)->delete();
            
            if ($deleted) {
                $message = array(true, 'Process Successful', 'Data updated successfully.', 'nextload(\'Yes\')');
                // $message = "Data berhasil dipindahkan dan dihapus dari table_a berdasarkan kondisi, limit, dan kolom dengan nilai null";
            } else {
                $message = array(false, 'Process Fails', 'The data could not be updated.', '');
                // $message = "Gagal menghapus data dari table_a berdasarkan kondisi, limit, dan kolom dengan nilai null setelah memindahkan ke table_b";
            }
        } else {
            $message = array(true, 'Process Successful', 'All Data updated successfully.', 'nextload(\'no\')');
            // $message = "Gagal memindahkan data ke table_b berdasarkan kondisi, limit, dan kolom dengan nilai null";
        }
        echo json_encode($message);
    }

    
    public function show_all()
    {
        echo 'aa';exit;
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            echo 'aa';exit;
        }else{
            return redirect('./#dashboard');
        }
    }
    /* ***** End move kolom ***** */
    
}
