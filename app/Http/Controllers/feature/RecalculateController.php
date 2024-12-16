<?php

namespace App\Http\Controllers\feature;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Models\Customer;
use App\Models\Project;
use App\Models\Cdr;
use App\Models\ProjectPrefixIp;
use App\Models\ProjectPrefixSrv;
use App\Models\Prefix;
use App\Models\sum\ReportCdr;
use App\Models\sum\ReportInvoice;

// harga
use App\Models\CustomerGroupPrice; // customer_group_prices
use App\Models\CustomerPrice; // customer_prices
use App\Models\ProjectPrice; // project_prices
use App\Models\PrefixGroup; // 

use App\Services\MyService;
use Illuminate\Support\Facades\DB;


class RecalculateController extends Controller
{

    private $_page_title = 'Recalculate CDR';
    private $_url_data = 'reportcdr';
    private $_id_role = '';
    private $_access_menu;
    private $_myService;
    private $_DataIDProjectByPrefix = null;
    private $_DataIDPrefix = null;
    private $_DataDestNoPrefixName_all = null;

    private $_DataProjectMember = null;
    private $_DataCustomerGroupPrice = null;
    private $_DataCustomerPrice = null;
    private $_DataProjectPrice = null;
    private $_DataReportCdr = null;
    private $_DataReportInvoice = null;
    private $_DatagetPrefixGroup = null;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_myService = app(MyService::class);
        $this->middleware('auth');
        $this->getProjectPrefixSrv();
        $this->getProjectIp();
        
        $this->getProjectMember();
        $this->getMemberPrice();
        $this->getCustomerPrice();
        $this->getMemberPrice();
        $this->getPrefixGroup();
        $this->getProjectPrice();
        $this->get_prefix_name_by_prefixNumber();

        // $this->getReportCdr();
        
        $this->middleware(function ($request, $next) {
            $this->_access_menu = $this->_myService->parser_access_menu($this->_url_data);
            return $next($request);
        });
    }

    public function getReportCdr()
    {
        $ReportCdrs = ReportCdr::get();
        $getReportCdrs = array();
        foreach ($ReportCdrs as $key => $val) {
            $getReportCdrs[$val->date][$val->idxCoreProject]['jmlCdr'] = $val->jmlCdr; // jumlah dimana data cdr tersebut memiliki 3 point relasi yaitu project ip dan prefix
            $getReportCdrs[$val->date][$val->idxCoreProject]['jmlPrefix'] = $val->jmlPrefix; // tarifTelkom
            $getReportCdrs[$val->date][$val->idxCoreProject]['jmlIP'] = $val->jmlIP;
            $getReportCdrs[$val->date][$val->idxCoreProject]['jmlWaktuReal'] = $val->jmlWaktuReal;
            $getReportCdrs[$val->date][$val->idxCoreProject]['jmlWaktuTagih'] = $val->jmlWaktuTagih;
            $getReportCdrs[$val->date][$val->idxCoreProject]['biayaTagih'] = $val->biayaTagih;
        }
        $this->_DataReportCdr = $getReportCdrs;
        return 'succsess';
        
    }
    
    public function getReportInvoice()
    {
        $ReportInvoices = ReportInvoice::get();
        $getReportInvoices = array();
        foreach ($ReportInvoices as $key => $val) {
            $getReportInvoices[$val->date][$val->idxCoreProject]['jmlCdr'] = $val->jmlCdr; // jumlah dimana data cdr tersebut memiliki 3 point relasi yaitu project ip dan prefix
            $getReportInvoices[$val->date][$val->idxCoreProject]['jmlPrefix'] = $val->jmlPrefix; // tarifTelkom
            $getReportInvoices[$val->date][$val->idxCoreProject]['jmlIP'] = $val->jmlIP;
            $getReportInvoices[$val->date][$val->idxCoreProject]['jmlWaktuReal'] = $val->jmlWaktuReal;
            $getReportInvoices[$val->date][$val->idxCoreProject]['jmlWaktuTagih'] = $val->jmlWaktuTagih;
            $getReportInvoices[$val->date][$val->idxCoreProject]['biayaTagih'] = $val->biayaTagih;
            $getReportInvoices[$val->date][$val->idxCoreProject]['tarifTelkom'] = $val->tarifTelkom;
            $getReportInvoices[$val->date][$val->idxCoreProject]['biayaTelkom'] = $val->biayaTelkom;
            $getReportInvoices[$val->date][$val->idxCoreProject]['penghematan'] = $val->penghematan;
        }
        $this->_DataReportInvoice = $getReportInvoices;
        return 'succsess';
        
    }


    public function getPrefixGroup()
    {
        $PrefixGroups = PrefixGroup::get();
        $getPrefixGroup = array();
        foreach ($PrefixGroups as $key => $val) {
            $getPrefixGroup[$val->nama] = $val->telkomPrice;
        }
        $this->_DatagetPrefixGroup = $getPrefixGroup;
        // ECHO count( $this->_DatagetPrefixGroup);
        // print_r( $this->_DatagetPrefixGroup);exit;
        return 'succsess';
        
    }

    public function getProjectPrefixSrv()
    {
        $ProjectPrefixSrvs = ProjectPrefixSrv::whereNotNull('IdxCoreProject')->get();
        $getIDProjectByPrefix = array();
        $getIDPrefix = array();
        foreach ($ProjectPrefixSrvs as $key => $val) {
            $getIDProjectByPrefix[$val->prefixNumber] = $val->idxCoreProject;
            $getIDPrefix[$val->prefixNumber] = $val->idxCore;
        }
        $this->_DataIDProjectByPrefix = $getIDProjectByPrefix;
        $this->_DataIDPrefix = $getIDPrefix;
        return 'succsess';
    }

    public function getProjectIp()
    {
        $ProjectIps = ProjectPrefixIp::whereNotNull('IdxCoreProject')->get();
        $getIps = array();
        foreach ($ProjectIps as $key => $val) {
            $getIps[$val->idxCoreProject][$val->idxCore]['categoryParser'] = $val->categoryParser;
            
            $getIps[$val->idxCoreProject][$val->idxCore]['startIPValue'] = $val->startIPValue;
            $getIps[$val->idxCoreProject][$val->idxCore]['endIPValue'] = $val->endIPValue;

            $getIps[$val->idxCoreProject][$val->idxCore]['startIP'] = $val->startIP;
            $getIps[$val->idxCoreProject][$val->idxCore]['endIP'] = $val->endIP;

            $getIps[$val->idxCoreProject][$val->idxCore]['startIPNumber'] = $val->startIPNumber;
            $getIps[$val->idxCoreProject][$val->idxCore]['endIpNumber'] = $val->endIpNumber;

        }
        $this->_DataIps = $getIps;
        return 'succsess';
    }

    public function getProjectMember() {
        $getProjectMembers = Project::whereNotNull('idxCoreCustGroup')->get();
        $getListProjectMembers = array();
        foreach ($getProjectMembers as $key => $val) {
            if ($val->idxCoreCustGroup !== null) {
                $getListProjectMembers[$val->idxCore] = $val->idxCoreCustGroup;
            }
        }
        
        $this->_DataProjectMember = $getListProjectMembers;
        return 'succsess';
    }
    
    public function getMemberPrice() {
        $Projects = CustomerGroupPrice::orderBy('created_at', 'ASC')->get();
        $getCustomerGroup = array();
        foreach ($Projects as $key => $val) {
            if(isset($val->idxCoreCustGroup) && $val->idxCoreCustGroup !== null) {
                $getCustomerGroups[$val->idxCoreCustGroup][$val->idxCore]['tarifPerMenit'] = $val->tarifPerMenit;
                $getCustomerGroups[$val->idxCoreCustGroup][$val->idxCore]['startRange'] = $val->startRange;
                $getCustomerGroups[$val->idxCoreCustGroup][$val->idxCore]['endRange'] = $val->endRange;
            }
        }
        $this->_DataCustomerGroupPrice = $getCustomerGroups;
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
            // $projects = Project::all();
            $projects = Project::where('statusData', 1)->get();
                
            $ProjectPrefixSrvs = ProjectPrefixSrv::where('active', 1)->get();
            return view('feature.recalculate.cdr_recalculate', [
                'page_title' => $this->_page_title,
                'projects' => $projects,
                'projectPrefixSrvs' => $ProjectPrefixSrvs
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }
    
    function extract_numbers($string) {
        $matches = [];
        // Ekspresi reguler untuk mengekstrak semua angka dalam string (Indonesian for 'Regular expression to extract all numbers in the string')
        preg_match_all('/\d+/', $string, $matches);
        return $matches[0];
    }

    public function update_cdr() 
    {
        
        $query = "SELECT * FROM `dev_billsystem`.`cdr` WHERE `reasonCode` IS NULL LIMIT 1000";
        $data_result = DB::select($query);
        
        if (!empty($data_result)) {
            
            foreach ($data_result as $value) {
                
                if(empty($idxCoreProjectIp) || empty($idxCorePrefix)) {
                    $call_data_server = array(
                        'idxCore' => $idxCore,
                        'destNoCust' => $destNoCust,
                        'destNoCustPrefix' => $destNoCustPrefix,
                        'idxCoreProject' => $idxCoreProject,
                        'idxCorePrefix' => $idxCorePrefix,
                        'idxCoreProjectIp' => $idxCoreProjectIp,
                        'idxCoreGroup' => $idxCoreGroup,
                        'idxCoreGroupPrice' => $idxCoreGroupPrice,
                        'custTime' => $custTime,
                        'custPrice' => $custPrice,
                        'telkomPrice' => $telkomPrice,
                        'destNoPrefixName' => $destNoPrefixName,
                        'ulangDouble' => $ulangDouble,
                        'reasonCode' => 0
                    );
                } else {
                    $call_data_server = array(
                        'idxCore' => $idxCore,
                        'destNoCust' => $destNoCust,
                        'destNoCustPrefix' => $destNoCustPrefix,
                        'idxCoreProject' => $idxCoreProject,
                        'idxCorePrefix' => $idxCorePrefix,
                        'idxCoreProjectIp' => $idxCoreProjectIp,
                        'idxCoreGroup' => $idxCoreGroup,
                        'idxCoreGroupPrice' => $idxCoreGroupPrice,
                        'custTime' => $custTime,
                        'custPrice' => $custPrice,
                        'telkomPrice' => $telkomPrice,
                        'destNoPrefixName' => $destNoPrefixName,
                        'ulangDouble' => $ulangDouble,
                        'reasonCode' => 3
                    );
                }
                // print_r ($call_data_server);exit;
                $dataToUpdate[] = $call_data_server;
            }
            
            $result = Cdr::upsert($dataToUpdate, ['idxCore'], ['idxCoreProject', 'idxCorePrefix', 'idxCoreProjectIp', 'idxCoreGroup', 'idxCoreGroupPrice',
                'destNoCust',
                'destNoCustPrefix',
                'custTime',
                'custPrice',
                'telkomPrice',
                'destNoPrefixName',
                'ulangDouble',
                'reasonCode']);
            
            if ($result > 0) {
                $message = array(true, 'Process Successful', 'Data updated successfully.', 'nextload(\'Yes\')');
            }else{
                
                $call_data_serverK2 = array(
                    'idxCore' => $idx,
                    'reasonCode' => 'data bemasalah'
                );
                $dataToUpdateK2[] = $call_data_serverK2;

                $result_ke2 = Cdr::upsert($dataToUpdateK2, ['idxCore'], ['idxCoreProject',
                    'idxCorePrefix',
                    'idxCoreProjectIp',
                    'destNoCust',
                    'destNoCustPrefix',
                    'custTime',
                    'custPrice',
                    'reasonCode']);
                if ($result_ke2 > 0) {
                    $message = array(true, 'Process Successful', 'Data updated successfully. Next loop...', 'nextload(\'Yes\')');
                }else{
                    $message = array(false, 'Process Fails', 'The data could not be updated.', '');
                }
            }
            
            
        }else{
            $message = array(true, 'Process Successful', 'All Data updated successfully.', 'nextload(\'no\')');
        }
        echo json_encode($message);
    }


    // all sudah benar --> ini yg akan digunakan untuk recalculate
    // public function update_cdr() // digunakan untuk recalculate
    public function update_cdr_all_sudah_benar() 
    {
        
        $query = "SELECT * FROM `dev_billsystem`.`cdr` WHERE `reasonCode` IS NULL LIMIT 1000";
        $data_result = DB::select($query);
        
        if (!empty($data_result)) {
            
            foreach ($data_result as $value) {
                
                $idxCore = $value->idxCore;
                $destNo = $value->destNo;
                $elapsedTime = $value->elapsedTime;
                //==================================================================> pengecekan sourceIPOnly 
                $sourceIPOnly = $value->sourceIPOnly;

                $destNoCust = Null;
                $destNoCustPrefix = Null; // kolom destNoCustPrefix ini untuk no frefix
                $idxCoreProject = Null;
                $idxCorePrefix = Null;
                $idxCoreProjectIp = Null;
                $idxCoreGroup = Null;
                $idxCoreGroupPrice = Null;
                $ulangDouble = 0;

                // destNoCust
                $spliteDestNo = substr($destNo, 4);
                $checkDestNoCust = substr($destNo, 4, 1);
                if($checkDestNoCust == 0){
                    $destNoCust = '62' . substr($spliteDestNo, 1);
                }
                $chekNo021 = substr($destNo, 4, 3);
                $chekNo0211 = substr($destNo, 4, 4);

                // destNoCustPrefix
                // semua di kurangi satu karena di rubah dari 0 menjadi 62
                $take_data = array(4,5,3,2,'all');
                foreach ($take_data as $value) {
                    if ($value == 'all' && $chekNo021 == '021') {
                        $destNoCustPrefix = substr($destNo, 0, (int)$value);
                        if (isset($this->_DataIDProjectByPrefix[$destNoCust])) {

                            // ======================> 
                            // ===========================> tahapan untuk memvalidasi data frefix, IP, Project (reason code 0 di recalculate)
                            // frefix
                            // ======> IP
                            // ======> Project
                            // $idxCoreProject = $this->_DataIDProjectByPrefix[$destNoCust];
                            // $idxCorePrefix = $this->_DataIDPrefix[$destNoCust];
                            
                            break;
                        }
                    } else {
                        $destNoCustPrefix = substr($destNo, 0, (int)$value);
                        if($checkDestNoCust == 0){
                            $destNoCust = '62' . substr($spliteDestNo, 1);
                        }
                        if (isset($this->_DataIDProjectByPrefix[$destNoCustPrefix])) {
                            // ======================> 
                            // ===========================> tahapan untuk memvalidasi data frefix, IP, Project (reason code 0 di recalculate)
                            // frefix
                            // ======> IP
                            // ======> Project
                            $idxCoreProject = $this->_DataIDProjectByPrefix[$destNoCustPrefix];
                            $idxCorePrefix = $this->_DataIDPrefix[$destNoCustPrefix];
                            break;
                        }
                    }
                }

                // =====================================> pengecekan data doeble file
                // =====================================> jika data terbaru lebih banyak maka ambil data terbaru dan delete data lama
                // =====================================> jika data terbaru banyaknya sama maka data terlama yg diambil
                
                // if(!empty($idxCorePrefix)) {
                //     if (isset($this->_DataIps[$idxCoreProject])) {
                //         $loopIpsByProject = $this->_DataIps[$idxCoreProject];
                //         $decimalSourceIPOnly = ip2long($sourceIPOnly);
                //         foreach($loopIpsByProject as $indIP => $valIP){
                //             $start = $valIP['start'];
                //             $end = $valIP['end'];
                //             if ($decimalSourceIPOnly >= $start && $decimalSourceIPOnly <= $end) {
                //                 $idxCoreProjectIp = $indIP;
                //                 break;
                //             }
                //         }
                //     }
                // }
                
                if(!empty($idxCorePrefix)) {
                    if (isset($this->_DataIps[$idxCoreProject])) {
                        $loopIpsByProject = $this->_DataIps[$idxCoreProject];
                        foreach($loopIpsByProject as $indIP => $valIP){

                            $startIP = $valIP['startIP'];
                            if ($sourceIPOnly == $startIP) {
                                $idxCoreProjectIp = $indIP;
                                $ulangDouble = 3;
                                break;
                            }
                            
                            if($ulangDouble != 3){
                                $needle = "SIP";
                                // echo "Substring found // ada sip";
                                if (strpos($sourceIPOnly, $needle) !== false) {
                                    $startIPNumber = $valIP['startIPNumber'];
                                    $endIpNumber = $valIP['endIpNumber'];
                                    $decimalSourceIPOnly = $this->extract_numbers($sourceIPOnly);
                                    if ($decimalSourceIPOnly >= $startIPNumber && $decimalSourceIPOnly <= $endIpNumber) {
                                        $idxCoreProjectIp = $indIP;
                                        $ulangDouble = 3;
                                        break;
                                    }
                                }

                                if($ulangDouble == 2){
                                    // echo "Substring found / tidak ada sip";
                                    if (strpos($sourceIPOnly, $needle) === false) {
                                        $startIPValue = ip2long($valIP['startIPValue']);
                                        $endIPValue = ($valIP['endIPValue']);
                                        $ip2long_sourceIPOnly = ip2long($sourceIPOnly);
                                        if ($ip2long_sourceIPOnly >= $startIPValue && $ip2long_sourceIPOnly <= $endIPValue) {
                                            $idxCoreProjectIp = $indIP;
                                            $ulangDouble = 3;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
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
                    if (isset($this->_DataCustomerPrice[$idxCoreProject][$destNoPrefixName])) {
                        $destNoPrefix = $value['destNoPrefix'];
                        $destNoPrefixName = $value['destNoPrefixName'];
                        break;
                    }
                }

                $custTime = Null;
                if (!empty($elapsedTime)) {
                    $custTime = $elapsedTime;
                    // if (preg_match("/\bpremium\b/i", $destNoPrefixName)) {
                    if ($chekNo0211 == '0211') {
                        $hasil = $custTime / 60;
                        $hasil_bulat = round($hasil);
                        $roundCustTimeData = $hasil_bulat * 60;
                        $custTime = $roundCustTimeData;
                    }
                }
                
                $custPrice = Null;
                // Cek apakah idxCoreProject ada dalam _DataCustomerGroup
                if (isset($this->_DataProjectMember[$idxCoreProject])) {
                    $idxCoreGroup = $this->_DataProjectMember[$idxCoreProject];
                    
                    // Cek apakah idxCoreProjectGroup ada dalam _DataCustomerGroupPrice
                    if (isset($this->_DataCustomerGroupPrice[$idxCoreGroup])) {
                        $idxCoreGroupPrices = $this->_DataCustomerGroupPrice[$idxCoreGroup];
                        $bigTime = 0;
                        $bigPrice = 0;
                        $bigIdxCoreGroupPrices = 0;
                        foreach ($idxCoreGroupPrices as $idxCoreGroupPrices => $valueGroupPrice) {
                            $tarifPerMenit = $valueGroupPrice['tarifPerMenit']; 
                            $startRange = $valueGroupPrice['startRange']; 
                            $endRange = $valueGroupPrice['endRange'];
                            
                            if (!empty($custTime)) {
                                if ($custTime >= $startRange && $custTime <= $endRange) {
                                    $custPrice = $tarifPerMenit;
                                    $idxCoreGroupPrice = $idxCoreGroupPrices;
                                }
                            }
                            
                            if ($endRange >= $bigTime) {
                                $bigTime = $endRange; 
                                $bigPrice = $tarifPerMenit; 
                                $bigIdxCoreGroupPrices = $idxCoreGroupPrices;
                            }

                        }

                        if ($custTime >= $bigTime){
                            $custPrice = $bigPrice;
                            $idxCoreGroupPrice = $bigIdxCoreGroupPrices;
                        }
                    }
                }

                // Jika custPrice masih null, coba ambil harga langsung dari _DataCustomerPrice
                if ($custPrice == null && isset($this->_DataCustomerPrice[$idxCoreProject][$destNoPrefixName])) {
                    $custPrice = $this->_DataCustomerPrice[$idxCoreProject][$destNoPrefixName];
                }

                // Jika custPrice masih null, coba ambil harga langsung dari _DataProjectPrice
                if ($custPrice == null && isset($this->_DataProjectPrice[$idxCoreProject])) {
                    // if (preg_match("/\bpremium\b/i", $destNoPrefixName)) {
                    if ($chekNo021 == '021') {
                        $custPrice = $this->_DataProjectPrice[$idxCoreProject]['pricePSTN'];
                        if ($chekNo0211 == '0211') {
                            $custPrice = $this->_DataProjectPrice[$idxCoreProject]['pricePremium'];
                        }
                    }
                    if ($custPrice == 0) {
                        $custPrice = $this->_DataProjectPrice[$idxCoreProject]['priceMobile'];
                    }
                }

                // Jika custPrice masih null, coba ambil harga langsung dari _DataCustomerPrice *ini diperuntukan untuk data2 portal
                if ($custPrice == null && isset($this->_DataCustomerPrice[$idxCoreProject]['Rest of Mobile'])) {
                    $destNoPrefixName = 'Rest of Mobile';
                    $custPrice = $this->_DataCustomerPrice[$idxCoreProject]['Rest of Mobile'];
                }
                
                $telkomPrice = 0;
                if ($destNoPrefixName === Null) {
                    if (isset($this->_DataDestNoPrefixName_all[$destNoPrefix])) {
                        $destNoPrefixName = $this->_DataDestNoPrefixName_all[$destNoPrefix];
                    }
                }
                
                if(isset($this->_DatagetPrefixGroup[$destNoPrefixName])) {
                    $telkomPrice = $this->_DatagetPrefixGroup[$destNoPrefixName];
                }
                
                if(empty($idxCoreProjectIp) || empty($idxCorePrefix)) {
                    $call_data_server = array(
                        'idxCore' => $idxCore,
                        'destNoCust' => $destNoCust,
                        'destNoCustPrefix' => $destNoCustPrefix,
                        'idxCoreProject' => $idxCoreProject,
                        'idxCorePrefix' => $idxCorePrefix,
                        'idxCoreProjectIp' => $idxCoreProjectIp,
                        'idxCoreGroup' => $idxCoreGroup,
                        'idxCoreGroupPrice' => $idxCoreGroupPrice,
                        'custTime' => $custTime,
                        'custPrice' => $custPrice,
                        'telkomPrice' => $telkomPrice,
                        'destNoPrefixName' => $destNoPrefixName,
                        'ulangDouble' => $ulangDouble,
                        'reasonCode' => 0
                    );
                } else {
                    $call_data_server = array(
                        'idxCore' => $idxCore,
                        'destNoCust' => $destNoCust,
                        'destNoCustPrefix' => $destNoCustPrefix,
                        'idxCoreProject' => $idxCoreProject,
                        'idxCorePrefix' => $idxCorePrefix,
                        'idxCoreProjectIp' => $idxCoreProjectIp,
                        'idxCoreGroup' => $idxCoreGroup,
                        'idxCoreGroupPrice' => $idxCoreGroupPrice,
                        'custTime' => $custTime,
                        'custPrice' => $custPrice,
                        'telkomPrice' => $telkomPrice,
                        'destNoPrefixName' => $destNoPrefixName,
                        'ulangDouble' => $ulangDouble,
                        'reasonCode' => 3
                    );
                }
                // print_r ($call_data_server);exit;
                $dataToUpdate[] = $call_data_server;
            }
            
            $result = Cdr::upsert($dataToUpdate, ['idxCore'], ['idxCoreProject', 'idxCorePrefix', 'idxCoreProjectIp', 'idxCoreGroup', 'idxCoreGroupPrice',
                'destNoCust',
                'destNoCustPrefix',
                'custTime',
                'custPrice',
                'telkomPrice',
                'destNoPrefixName',
                'ulangDouble',
                'reasonCode']);
            
            if ($result > 0) {
                $message = array(true, 'Process Successful', 'Data updated successfully.', 'nextload(\'Yes\')');
            }else{
                
                $call_data_serverK2 = array(
                    'idxCore' => $idx,
                    'reasonCode' => 'data bemasalah'
                );
                $dataToUpdateK2[] = $call_data_serverK2;

                $result_ke2 = Cdr::upsert($dataToUpdateK2, ['idxCore'], ['idxCoreProject',
                    'idxCorePrefix',
                    'idxCoreProjectIp',
                    'destNoCust',
                    'destNoCustPrefix',
                    'custTime',
                    'custPrice',
                    'reasonCode']);
                if ($result_ke2 > 0) {
                    $message = array(true, 'Process Successful', 'Data updated successfully. Next loop...', 'nextload(\'Yes\')');
                }else{
                    $message = array(false, 'Process Fails', 'The data could not be updated.', '');
                }
            }
            
            
        }else{
            $message = array(true, 'Process Successful', 'All Data updated successfully.', 'nextload(\'no\')');
        }
        echo json_encode($message);
    }
    
    // hanya untuk mengatasi data double lrbih ke arah perbaikan ip
    public function update_cdr_double()
    {

        // echo '123';exit;
        // $query = "SELECT * FROM `dev_billsystem`.`cdr` WHERE `reasonCode` = '3' AND `ulangDouble` = '1' LIMIT 0,1000;";
        // $query = "SELECT * FROM `dev_billsystem`.`cdr` WHERE `destNoCustPrefix` = '5754' AND `reasonCode` = '3' AND `serverData` = 'ELASTIX' ORDER BY `folderName` LIMIT 0,1;";
        $query = "SELECT * FROM `dev_billsystem`.`cdr` WHERE `ulangDouble` = '1'  LIMIT 0,1000;";
        $data_result = DB::select($query);
        
        if (!empty($data_result)) {
            
            foreach ($data_result as $value) {
                // print_r($value);exit;
                $idxCoreProjectIp = '';

                $ulangDouble = 2;
                $idxCore = $value->idxCore; 
                $sourceIPOnly = $value->sourceIPOnly;
                $idxCoreProject = $value->idxCoreProject;
                $idxCorePrefix = $value->idxCorePrefix;
                
                if(!empty($idxCorePrefix)) {
                    if (isset($this->_DataIps[$idxCoreProject])) {
                        $loopIpsByProject = $this->_DataIps[$idxCoreProject];
                        foreach($loopIpsByProject as $indIP => $valIP){

                            $startIP = $valIP['startIP'];
                            if ($sourceIPOnly == $startIP) {
                                $idxCoreProjectIp = $indIP;
                                $ulangDouble = 3;
                                break;
                            }

                            if($ulangDouble == 2){
                                $needle = "SIP";
                                // echo "Substring found // ada sip";
                                if (strpos($sourceIPOnly, $needle) !== false) {
                                    $startIPNumber = $valIP['startIPNumber'];
                                    $endIpNumber = $valIP['endIpNumber'];
                                    $decimalSourceIPOnly = $this->extract_numbers($sourceIPOnly);
                                    if ($decimalSourceIPOnly >= $startIPNumber && $decimalSourceIPOnly <= $endIpNumber) {
                                        $idxCoreProjectIp = $indIP;
                                        $ulangDouble = 3;
                                        break;
                                    }
                                }

                                if($ulangDouble == 2){
                                    // echo "Substring found / tidak ada sip";
                                    if (strpos($sourceIPOnly, $needle) === false) {
                                        $startIPValue = ip2long($valIP['startIPValue']);
                                        $endIPValue = ($valIP['endIPValue']);
                                        $ip2long_sourceIPOnly = ip2long($sourceIPOnly);
                                        if ($ip2long_sourceIPOnly >= $startIPValue && $ip2long_sourceIPOnly <= $endIPValue) {
                                            $idxCoreProjectIp = $indIP;
                                            $ulangDouble = 3;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                
                $call_data_server = array(
                    'idxCore' => $idxCore,
                    'ulangDouble' => $ulangDouble
                );
                
                $dataToUpdate[] = $call_data_server;
            }
            
            $result = Cdr::upsert($dataToUpdate, ['idxCore'], [
                'ulangDouble'
            ]);

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
    
    // hanya untuk menambahkan telkomPrice nanti tambahkan juga untuk sistem Topologi CDR yg lainnya
    public function update_cdr_ori ()
    {
        // echo '123';exit;
        // $query = "SELECT * FROM `dev_billsystem`.`cdr` WHERE telkomPrice is Null  LIMIT 1;";
        // $query = "SELECT idxCore, destNoPrefix, destNoPrefixName FROM `dev_billsystem`.`cdr` WHERE `telkomPrice` IS NULL AND `reasonCode` = '3' LIMIT 0, 3000;";
        $query = "SELECT idxCore, destNoPrefix, destNoPrefixName FROM `dev_billsystem`.`cdr` WHERE `telkomPrice` IS NULL LIMIT 3000;";
        $data_result = DB::select($query);
        
        if (!empty($data_result)) {
            
            foreach ($data_result as $value) {
                $idxCore = $value->idxCore;
                $destNoPrefix = $value->destNoPrefix;
                $destNoPrefixName = $value->destNoPrefixName;
                
                if ($destNoPrefixName === Null) {
                    if (isset($this->_DataDestNoPrefixName_all[$destNoPrefix])) {
                        $destNoPrefixName = $this->_DataDestNoPrefixName_all[$destNoPrefix];
                    }
                }

                if(isset($this->_DatagetPrefixGroup[$destNoPrefixName])) {
                    $telkomPrice = $this->_DatagetPrefixGroup[$destNoPrefixName];
                }
                
                
                $call_data_server = array(
                    'idxCore' => $idxCore,
                    'telkomPrice' => $telkomPrice,
                    'destNoPrefixName' => $destNoPrefixName
                );
                // print_r($value);exit;
                $dataToUpdate[] = $call_data_server;
            }
            
            $result = Cdr::upsert($dataToUpdate, ['idxCore'], [
                'telkomPrice',
                'destNoPrefixName'
            ]);

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
   
    public function update_cdr_hanya_untuk_premium()
    {
        // echo '123';exit;
        $query = "SELECT * FROM `dev_billsystem`.`cdr` WHERE `ulangTimePrice` = '1' LIMIT 0,2000;";
        $data_result = DB::select($query);
        
        if (!empty($data_result)) {
            
            foreach ($data_result as $value) {
                $idxCore = $value->idxCore;
                $destNo = $value->destNo;
                $elapsedTime = $value->elapsedTime;
                $sourceIPOnly = $value->sourceIPOnly;
                $idxCoreProject = $value->idxCoreProject;
                $destNoPrefixName = $value->destNoPrefixName;
                
                // destNoCust
                $spliteDestNo = substr($destNo, 4);
                $checkDestNoCust = substr($destNo, 4, 1);
                if($checkDestNoCust == 0){
                    $destNoCust = '62' . substr($spliteDestNo, 1);
                }
                $chekNo021 = substr($destNo, 4, 3);
                $chekNo0211 = substr($destNo, 4, 4);
                
                $hasil = $elapsedTime / 60;
                $hasil_bulat = ceil($hasil);
                $ceilCustTimeData = $hasil_bulat * 60;
                $custTime = $ceilCustTimeData;
                
                $custPrice = Null;
                // Cek apakah idxCoreProject ada dalam _DataCustomerGroup
                if (isset($this->_DataProjectMember[$idxCoreProject])) {
                    $idxCoreGroup = $this->_DataProjectMember[$idxCoreProject];
                    
                    // Cek apakah idxCoreProjectGroup ada dalam _DataCustomerGroupPrice
                    if (isset($this->_DataCustomerGroupPrice[$idxCoreGroup])) {
                        $idxCoreGroupPrices = $this->_DataCustomerGroupPrice[$idxCoreGroup];
                        $bigTime = 0;
                        $bigPrice = 0;
                        $bigIdxCoreGroupPrices = 0;
                        foreach ($idxCoreGroupPrices as $idxCoreGroupPrices => $valueGroupPrice) {
                            $tarifPerMenit = $valueGroupPrice['tarifPerMenit']; 
                            $startRange = $valueGroupPrice['startRange']; 
                            $endRange = $valueGroupPrice['endRange'];
                            
                            if (!empty($custTime)) {
                                if ($custTime >= $startRange && $custTime <= $endRange) {
                                    $custPrice = $tarifPerMenit;
                                    $idxCoreGroupPrice = $idxCoreGroupPrices;
                                }
                            }
                            
                            if ($endRange >= $bigTime) {
                                $bigTime = $endRange; 
                                $bigPrice = $tarifPerMenit; 
                                $bigIdxCoreGroupPrices = $idxCoreGroupPrices;
                            }

                        }

                        if ($custTime >= $bigTime){
                            $custPrice = $bigPrice;
                            $idxCoreGroupPrice = $bigIdxCoreGroupPrices;
                        }
                    }
                }

                // Jika custPrice masih null, coba ambil harga langsung dari _DataCustomerPrice
                if ($custPrice == null && isset($this->_DataCustomerPrice[$idxCoreProject][$destNoPrefixName])) {
                    $custPrice = $this->_DataCustomerPrice[$idxCoreProject][$destNoPrefixName];
                }
                
                // Jika custPrice masih null, coba ambil harga langsung dari _DataProjectPrice
                if ($custPrice == null && isset($this->_DataProjectPrice[$idxCoreProject])) {
                    // if (preg_match("/\bpremium\b/i", $destNoPrefixName)) {
                    if ($chekNo021 == '021') {
                        $custPrice = $this->_DataProjectPrice[$idxCoreProject]['pricePSTN'];
                        if ($chekNo0211 == '0211') {
                            $custPrice = $this->_DataProjectPrice[$idxCoreProject]['pricePremium'];
                        }
                    }
                    if ($custPrice == 0) {
                        $custPrice = $this->_DataProjectPrice[$idxCoreProject]['priceMobile'];
                    }
                }

                // Jika custPrice masih null, coba ambil harga langsung dari _DataCustomerPrice *ini diperuntukan untuk data2 portal
                if ($custPrice == null && isset($this->_DataCustomerPrice[$idxCoreProject]['Rest of Mobile'])) {
                    $destNoPrefixName = 'Rest of Mobile';
                    $custPrice = $this->_DataCustomerPrice[$idxCoreProject]['Rest of Mobile'];
                }
                
                $call_data_server = array(
                    'idxCore' => $idxCore,
                    'custPrice' => $custPrice
                );
                
                $dataToUpdate[] = $call_data_server;
            }
            
            $result = Cdr::upsert($dataToUpdate, ['idxCore'], [
                'custPrice'
            ]);

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
    /* ***** Kebutuhan Customer ***** */
}
