<?php
///////////////////////////////////////////////////////////////////////// cari tulisan pengecekan. itu yg belum
namespace App\Http\Controllers\feature;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Services\MyService;
use Illuminate\Support\Facades\DB;
use App\Services\CalculateService;
use App\Models\Cdr;

use App\Models\monitoring\ParserCsv;
use App\Models\monitoring\RepairCsv;
use App\Models\monitoring\SettingParser;
use App\Models\monitoring\CheckFolderFile;
use App\Models\Project;
use App\Models\Prefix;
use App\Models\ProjectPrefixIp;
// use App\Models\CustomerIp;
// use App\Models\CustomerIpPrefix;
use App\Models\ProjectPrefixSrv;
// use App\Models\Supplier;
// use App\Models\SupplierIp;
// use App\Models\SupplierIpPrefix;
// use App\Models\SupplierPrice;

// harga
use App\Models\CustomerGroupPrice; // customer_group_prices
use App\Models\CustomerPrice; // customer_prices
use App\Models\ProjectPrice; // project_prices
use App\Models\PrefixGroup; // 
use App\Models\NoCdr; // 


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
    private $_DataProjectPrice = null;
    private $_DataSourceIpFixed = null;
    private $_DataSourceIpValue = null;
    private $_DataIDProjectByPrefix = null;
    private $_DataProjectMember = null;
    private $_DatagetPrefixGroup = null;
    private $_DatagetFolderFile = null;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_myService = app(MyService::class);
        $this->_CalculateService = app(CalculateService::class);
        $this->getProjectPrefixSrv();
        $this->getProjectIp();
        $this->getProjectMember();
        $this->getMemberPrice();
        $this->get_prefix_name_by_prefixNumber();
        $this->getPrefixGroup();
        $this->getProjectPrice();
        $this->getCustomerPrice();
        $this->getFolderFile();
        
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->_access_menu = $this->_myService->parser_access_menu($this->_url_data);
            return $next($request);
        });
    }

    public function getProjectDetail()
    {
        $ProjectDetails = Project::get();
        $getProjectDetailsCLI = array();
        $getProjectDetailsFreeTrial = array();
        foreach ($ProjectDetails as $key => $val) {

            // pengambilan data untuk cli
            if($val->isCLI){
                $getProjectDetailsCLI[$val->idxCore] = 'isCLI';
            }
            
            // pengambilan data untuk free trial
            $getProjectDetailsFreeTrial[$val->idxCore]['startFT'] = $val->startFT;
            $getProjectDetailsFreeTrial[$val->idxCore]['endFT'] = $val->endFT;

            // pengambilan data untuk free trial
            $getProjectDetailsFreeTrial[$val->idxCore]['startFT'] = $val->startFT;
            $getProjectDetailsFreeTrial[$val->idxCore]['endFT'] = $val->endFT;
        }
        $this->_DataProjectDetailsCLI = $getProjectDetailsCLI;
        $this->_DataProjectDetailsFreeTrial = $getProjectDetailsFreeTrial;
        return 'succsess';
    }

    public function getProjectPrefixSrv()
    {
        $ProjectPrefixSrvs = ProjectPrefixSrv::whereNotNull('IdxCoreProject')->get();
        $getIDProjectByPrefix = array();
        $getIDPrefix = array();
        foreach ($ProjectPrefixSrvs as $key => $val) {

            // if (isset($getIDProjectByPrefix[$val->prefixNumber])) {
                
            // }

            $getIDProjectByPrefix[$val->prefixNumber][] = $val->idxCoreProject;
            $getIDPrefix[$val->prefixNumber][$val->idxCoreProject] = $val->idxCore;
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

    public function getProjectMember()
    {
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

    public function getMemberPrice()
    {
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

    public function get_prefix_name_by_prefixNumber()
    {
        $prefixes = Prefix::get();
        $getDestNoPrefixName_all = array();
        foreach ($prefixes as $key => $val) {
            $getDestNoPrefixName_all[$val->prefixNumber] = $val->prefixName;
        }
        $this->_DataDestNoPrefixName_all = $getDestNoPrefixName_all;
        
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
        return 'succsess';
        
    }

    public function getProjectPrice()
    {
        $ProjectPrices = ProjectPrice::get();
        $getDataCustomerPrice = array();
        foreach ($ProjectPrices as $key => $val) {
            $getDataProjectPrice[$val->idxCoreProject]['pricePSTN'] = $val->pricePSTN;
            $getDataProjectPrice[$val->idxCoreProject]['priceMobile'] = $val->priceMobile;
            $getDataProjectPrice[$val->idxCoreProject]['pricePremium'] = $val->pricePremium;
            $getDataProjectPrice[$val->idxCoreProject]['price'] = $val->price;
        }
        $this->_DataProjectPrice = $getDataProjectPrice;
        // print_r ($this->_DataProjectPrice);exit;
        
        return 'succsess';
    }

    public function getCustomerPrice()
    {
        $CustomerPrices = CustomerPrice::get();
        $getDataCustomerPrice = array();
        foreach ($CustomerPrices as $key => $val) {
            $getDataCustomerPrice[$val->idxCoreProjectFDesktop][$val->prefixName] = $val->tarifPerMenit;
        }
        $this->_DataCustomerPrice = $getDataCustomerPrice;
        // print_r ($this->_DataCustomerPrice);exit;
        
        return 'succsess';
    }

    public function getFolderFile()
    {
        // keterangan status
        // Null : data bukan double
        // 1 : data double selesai di benerin dan digunakan sebagai data cikal bakal cdr
        // 2 : data yg memiliki double tetapi tidak di gunakan
        // 3 : data xample
        $CheckFolderFiles = CheckFolderFile::where('statusDouble', 2)
            ->orWhere('statusDouble', 3)
            ->get();
            // echo count ( $CheckFolderFiles);
        $getDataCheckFolderFiles = array();
        foreach ($CheckFolderFiles as $key => $val) {
            $getDataCheckFolderFiles[$val->folderName][$val->fileName] = $val->statusDouble;
        }
        $this->_DatagetFolderFile = $getDataCheckFolderFiles;
        // print_r ($this->_DatagetFolderFile);
        // echo count ($this->_DatagetFolderFile);exit;
        
        return 'succsess';
    }
    
    public function edit_cdr(string $idcore)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            $projects = Project::all();
            return view('feature.calculate.cdr_calculate', [
                'page_title' => $this->_page_title,
                'projects' => $projects
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }

    function extract_numbers($string)
    {
        $matches = [];
        // Ekspresi reguler untuk mengekstrak semua angka dalam string (Indonesian for 'Regular expression to extract all numbers in the string')
        preg_match_all('/\d+/', $string, $matches);
        return $matches[0];
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
    
    public function update_cdr()
    {
        // echo 'ok';exit;
        // $limitDataParser = 1500;
        $limitDataParser = 1500;
        $dataToInsertCDR = array();
        $dataToInsertRepairCSV = array();
        $dataToInsertRepairCDR = array();
        $dataNoCdr = array();

        $data_result = ParserCsv::limit($limitDataParser)->get();
        // $data_result = ParserCsv::where('idxCore', '1f843b6e-07a6-11ef-9622-b47af1e58563')->get(); // [engecekan 1500 atau awalan angka 1]
        // $data_result = ParserCsv::where('idxCore', '0cc4b73c-07a6-11ef-9622-b47af1e58563')->get(); // [engecekan 1500 atau awalan angka 1]
        // $data_result = ParserCsv::where('idxCoreProject', '0c9ea039-0013-11ef-9626-b47af1e58563')->limit(1)->get(); // [engecekan 1500 atau awalan angka 1]
        // $data_result = ParserCsv::where('idxCore', '98ebc930-07a5-11ef-9622-b47af1e58563')->get(); // (destNoCustPrefix : 5739 || ELASTIX 88)
        // $data_result = ParserCsv::where('idxCore', '97f51b83-07a5-11ef-9622-b47af1e58563')->get(); (destNoCustPrefix : 5301)
        // $data_result = ParserCsv::where('idxCore', '04a251b3-07a6-11ef-9622-b47af1e58563')->get(); // (4747-PaninDai-ichiLife (PaninDai) - CLI - Office || MERA 86 || 900 )
        // $data_result = ParserCsv::where('idxCore', 'fcd20f59-07a5-11ef-9622-b47af1e58563')->get(); // nilai premium emang noll
        // $data_result = ParserCsv::where('idxCore', '061a9c72-07a6-11ef-9622-b47af1e58563')->get(); 
        // $data_result = ParserCsv::where('idxCore', '1ea1821f-07a6-11ef-9622-b47af1e58563')->get();
        
        // $data_result = ParserCsv::where('ulangTimePrice', '1')->limit(1)->get(); // ==> untuk cek premium
        // $data_result = ParserCsv::where('testingData', '1')->limit(1)->get();
        // $query = "SELECT * FROM `dev_billsystem`.`cdr` WHERE `ulangTimePrice` = '1' LIMIT 0,2000;";
        // $data_result = DB::select($query);
        // $data_result = ParserCsv::where('reasonCode', 3)
        // ->where('ulangDouble', 3)

        // $data_result = ParserCsv::where('testingData', 1)
        //     ->orderBy('folderName', 'asc')
        //     ->orderBy('fileName', 'asc')
        //     ->limit($limitDataParser)
        //     ->get();
        //     // ->toSql();
        //     // dd($data_result); // Melihat hasil data dan query SQL
            
        // $data_result = ParserCsv::where('idxCore', 'd4f71e3e-07a5-11ef-9622-b47af1e58563')->get(); // [engecekan 1500 atau awalan angka 1]
        // $data_result = ParserCsv::where('idxCore', 'dda976fc-b558-11ef-96a5-7cc2c6530011')->get(); // [engecekan 1500 atau awalan angka 1]

        // print_r ($data_result);exit;
        $setting_parsers = SettingParser::with('setting_parser_regex', 'setting_parser_row')->get();
        $updates = [];
        
        if (!$data_result->isEmpty() && $data_result->count() >= 1 && !$setting_parsers->isEmpty()) {
            
            $arrayDataToInsertCDR = [];
            $arrayDataToInsertRepairCSV = [];
            $dataNoCdr = [];
            
            foreach ($data_result as $valueDataResult) {
                
                // $idxCore = $valueDataResult->idxCore;
                // $dataValueDataResult = preg_replace('/""([^"]+)"/', '"$1', $valueDataResult->data);
                // $valuesArray = str_getcsv($dataValueDataResult);
                // print_r ($valuesArray);
                // exit;
                // $jmlKolPHP = count($valuesArray);
                // $parts1 = explode('/', $valueDataResult->readyPath);
                // $result1 = isset($parts1[count($parts1) - 2]) ? $parts1[count($parts1) - 2] : "Format path tidak sesuai pada path 1.";
                // echo $valueDataResult->elapsedTime;exit;
                
                
                $idxCore = $valueDataResult->idxCore;
                $parts1 = explode('/', $valueDataResult->readyPath);
                $result1 = isset($parts1[count($parts1) - 2]) ? $parts1[count($parts1) - 2] : "Format path tidak sesuai pada path 1.";

                $valuesArray = $this->parseCsvManual($valueDataResult->data);
                $jmlKolPHP = count($valuesArray);
                $idxCorePrefix = '';
                $setting_parser = array();
                $dataCallback = array();
                $data_default_result = [];
                $idxCoreCustGroup = Null;
                $idxCoreGroupPrice = Null;
                $idxCoreProject = Null;
                $idxCoreProjectIp = Null;
                $idxCorePrefix = Null;

                switch (true) {
                    case preg_match("/\b.*MERA.*\b/i", $result1):
                        $dataServer = 'MERA';
                        $setting_parser = $setting_parsers[0];
                        if ($setting_parser->teknik_parser == 'column'){
                            $call_data_server = $this->_CalculateService->readMERA($valuesArray, $setting_parser);
                        } else {
                            $call_data_server = $this->_CalculateService->readMERA($valueDataResult->data, $setting_parser);
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
                }
                // print_r($call_data_server);
                // exit;
                
                // dikhawatirkan node terlalu panjang dalam membuat clear data untuk ambil nama folder
                // ata dikhawatirkan node terlalu rumit untuk membuat clear data untuk ambil nama folder
                $folderName = $valueDataResult->folderName;
                if (empty($folderName)){
                    $dapatkan_nama_folder = $valueDataResult->ReadyPath;
                    // Pecah path menjadi array berdasarkan tanda '/'
                    $path_parts = explode('/', $dapatkan_nama_folder);
                    // Temukan indeks dari bagian terakhir path
                    $index = count($path_parts) - 2; // -2 karena bagian terakhir adalah file atau folder cdr_xxx
                    // Kembalikan bagian target
                    $folderName = $path_parts[$index];
                }
                
                // Memeriksa apakah 'sourceIPOnly' ada dan tidak kosong
                if (!empty($call_data_server['sourceIPOnly'])) {
                    $sourceIPOnly = $call_data_server['sourceIPOnly'];
                    // Temukan posisi terakhir dari tanda "-"
                    $pos = strrpos($sourceIPOnly, '-');
                    if ($pos !== false) {
                        // Ambil substring dari awal sampai posisi tanda "-"
                        $sourceIPOnly = substr($sourceIPOnly, 0, $pos);
                        $call_data_server['sourceIPOnly'] = $sourceIPOnly;
                    }
                }
                
                // chek data folder name dan file name jika data tersebut adalah double data
                // =====================================> Data Doeble File
                // =====================================> jika data terbaru lebih banyak maka ambil data terbaru dan delete data lama
                // =====================================> jika data terbaru banyaknya sama maka data terlama yg diambil
                if (isset($this->_DatagetFolderFile[$valueDataResult->folderName][$valueDataResult->fileName])) {

                        // echo 'masuk data non cdr';exit;
                        // dikhawatirkan node terlalu panjang dalam membuat clear data untuk ambil nama folder
                        // ata dikhawatirkan node terlalu rumit untuk membuat clear data untuk ambil nama folder
                        
                        $dataNoCdr = array(
                            'idxCore' => $idxCore,
                            'fileName' => $valueDataResult->fileName,
                            'folderName' => $folderName,
                            'readyPath' => $valueDataResult->readyPath,
                            'resultPath' => $valueDataResult->resultPath,
                            'dateTimeCSVtoDB' => $valueDataResult->dateTimeCSVtoDB,
                            'lineNumber' => $valueDataResult->lineNumber,
                            'serverData' => $dataServer,
                            'jmlKol' => $valueDataResult->jmlKol,
                            'jmlKolPHP' => $jmlKolPHP,
                            'data' => $valueDataResult->data,
                            'datetime' => $call_data_server['datetime'],
                            'sourceNo' => $call_data_server['sourceNo'],
                            'sourceNoOut' => $call_data_server['sourceNoOut'],
                            'sourceIP' => $call_data_server['sourceIP'],
                            'elapsedTime' => $call_data_server['elapsedTime'],
                            'destNo' => $call_data_server['destNo'],
                            'destNoOut' => $call_data_server['destNoOut'],
                            'destIP' => $call_data_server['destIP'],
                            'destName' => $call_data_server['destName'],
                            'destIPOnly' => $call_data_server['destIPOnly'],
                            'sourceIPOnly' => $call_data_server['sourceIPOnly']
                        );
                        $dataToUpdateFor = array(
                            'idxCore' => $idxCore,
                            'testingData' => 0
                        );
                        // print_r($value);exit;
                        $dataToUpdate[] = $dataToUpdateFor;

                }else{
                    
                    // echo 'aaaaaaaa cdr';exit;
                    // destNoCust
                    $destNo = $call_data_server['destNo'];
                    $spliteDestNo = substr($destNo, 4);
                    // echo $spliteDestNo;exit;
                    $checkDestNoCust = substr($destNo, 4, 1);
                    $checkDestNoCust62 = substr($destNo, 4, 2);
                    $destNoCust = '';
                    if($checkDestNoCust == 0){
                        $destNoCust = '62' . substr($spliteDestNo, 1);
                    }
                    if($checkDestNoCust62 == '62'){
                        $destNoCust =  $spliteDestNo ;
                    }
                    // echo $destNoCust;exit;
                    // echo $checkDestNoCust62;
                    // exit;
                    $chekNo021 = substr($destNo, 4, 3);
                    $chekNo0211 = substr($destNo, 4, 4);
                    
                    // destNoCustPrefix
                    // semua di kurangi satu karena di rubah dari 0 menjadi 62
                    $take_data = array(4,5,3,2,'all');
                    foreach ($take_data as $valueDestNoCustPrefix) {
                        
                        $destNoCustPrefix = substr($destNo, 0, (int)$valueDestNoCustPrefix);
                        if ($valueDestNoCustPrefix == 'all' && $chekNo021 == '021') {
                            if (isset($this->_DataIDProjectByPrefix[$destNoCust])) {
                                
                                // mendapatkan list project yg berhubungan
                                $dataRelasiProject = $this->_DataIDProjectByPrefix[$destNoCust];
                                // print_r($dataRelasiProject); exit;
                                foreach ($dataRelasiProject as $key => $tempIdxCoreProject) {
                                    
                                    // mendapatkan list IP berdasarkan IP Project
                                    if (isset($this->_DataIps[$tempIdxCoreProject])) {
                                        $loopIpsByProject = $this->_DataIps[$tempIdxCoreProject];
                                        // print_r($loopIpsByProject);exit;
                                        foreach($loopIpsByProject as $indIP => $valIP){
                                            
                                            // 1 : Singgle ~ sama dengan
                                            // 2 : Range ~ IP
                                            // 3 : Range ~ String
                                            switch ($valIP['categoryParser']) {
                                                case 1:
                                                    $startIP = $valIP['startIP'];
                                                    $sourceIPOnly = preg_replace('/\s+/', '', preg_replace('/\b0+(\d)/', '$1', $sourceIPOnly));
                                                    $startIP = preg_replace('/\s+/', '', preg_replace('/\b0+(\d)/', '$1', $startIP));
                                                    // $startIP = $valIP['startIP'];
                                                    // if ($sourceIPOnly == $startIP) {
                                                    if (strtoupper($sourceIPOnly) == strtoupper($startIP)) {
                                                        $idxCoreProject = $tempIdxCoreProject;
                                                        $idxCoreProjectIp = $indIP;
                                                        $idxCorePrefix = $this->_DataIDPrefix[$destNoCust][$idxCoreProject];
                                                        $chekData = 0;
                                                        break;
                                                    }
                                                    break;
                                                case 2:
                                                    // echo 'a222--';
                                                    // exit;
                                                    // chek jika data adalah IP4
                                                    if (filter_var($sourceIPOnly, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false) {
                                                        // echo 'a2222-2--';exit;

                                                        // Clear IP
                                                        $startIPValue = ip2long(preg_replace('/\b0+(\d)/', '$1', $valIP['startIP']));
                                                        $endIPValue = ip2long(preg_replace('/\b0+(\d)/', '$1', $valIP['endIP']));

                                                        $SourceIPOnlyValue = ip2long(preg_replace('/\b0+(\d)/', '$1', $sourceIPOnly));
                                                        if ($SourceIPOnlyValue >= $startIPValue && $SourceIPOnlyValue <= $endIPValue) {
                                                            $idxCoreProject = $tempIdxCoreProject;
                                                            $idxCoreProjectIp = $indIP;
                                                            $idxCorePrefix = $this->_DataIDPrefix[$destNoCust][$idxCoreProject];
                                                            $chekData = 0;
                                                        }
                                                    }
                                                    break;
                                                case 3:
                                                    // echo 'a333--';
                                                    // exit;
                                                    $needle = "SIP";
                                                    // chek jika data ada sip akan dilakukan condisional di bawah
                                                    if (strpos($sourceIPOnly, $needle) !== false) {
                                                        // echo 'a2222-1--';exit;
                                                        $startIPNumber = $valIP['startIPNumber'];
                                                        $endIpNumber = $valIP['endIpNumber'];
                                                        $decimalSourceIPOnly = $this->extract_numbers($sourceIPOnly);

                                                        // jika array ambil array no 0/ yg ke satu
                                                        if (is_array($decimalSourceIPOnly)) {
                                                            $decimalSourceIPOnly = $decimalSourceIPOnly[0];
                                                        }
                                                        if(!empty($decimalSourceIPOnly)){
                                                            if ($decimalSourceIPOnly >= $startIPNumber && $decimalSourceIPOnly <= $endIpNumber) {
                                                                $idxCoreProject = $tempIdxCoreProject;
                                                                $idxCoreProjectIp = $indIP;
                                                                $idxCorePrefix = $this->_DataIDPrefix[$destNoCust][$idxCoreProject];
                                                                $chekData = 0;
                                                            }
                                                        }
                                                    }
                                                
                                                    break;
                                            }
                                        }
                                    }
                                }
                                break;
                            }
                        } else {
                            // echo "bbb $destNoCustPrefix";
                            // exit;
                            if($checkDestNoCust == 0){
                                $destNoCust = '62' . substr($spliteDestNo, 1);
                            }
                            if (isset($this->_DataIDProjectByPrefix[$destNoCustPrefix])) {
                                // echo "mendapatkan list project yg berhubungan";
                                // exit; 
                                // mendapatkan list project yg berhubungan
                                $dataRelasiProject = $this->_DataIDProjectByPrefix[$destNoCustPrefix];
                                // print_r($dataRelasiProject);
                                // exit;
                                foreach ($dataRelasiProject as $key => $tempIdxCoreProject) {
                                    // echo $tempIdxCoreProject;
                                    // exit;
                                    // mendapatkan list IP berdasarkan IP Project
                                    if (isset($this->_DataIps[$tempIdxCoreProject])) {
                                        $loopIpsByProject = $this->_DataIps[$tempIdxCoreProject];
                                        // print_r($loopIpsByProject);
                                        // exit;
                                        foreach($loopIpsByProject as $indIP => $valIP){
                                            
                                            // if ($indIP == '11f1b657-ffc6-11ee-9626-b47af1e58563'){
                                            // if ($tempIdxCoreProject == '0c9eab9e-0013-11ef-9626-b47af1e58563'){
                                            // if ($indIP == '0c9ed731-0013-11ef-9626-b47af1e58563'){
                                                // 1 : Singgle ~ sama dengan
                                                // 2 : Range ~ IP
                                                // 3 : Range ~ String
                                                switch ($valIP['categoryParser']) {
                                                    case 1:
                                                        // echo 'a1111--';
                                                        // exit;
                                                        $startIP = $valIP['startIP'];
                                                        $sourceIPOnly = preg_replace('/\s+/', '', preg_replace('/\b0+(\d)/', '$1', $sourceIPOnly));
                                                        $startIP = preg_replace('/\s+/', '', preg_replace('/\b0+(\d)/', '$1', $startIP));
                                                        // if ($sourceIPOnly == $startIP) {
                                                        if (strtoupper($sourceIPOnly) == strtoupper($startIP)) {
                                                            $idxCoreProject = $tempIdxCoreProject;
                                                            $idxCoreProjectIp = $indIP;
                                                            $idxCorePrefix = $this->_DataIDPrefix[$destNoCustPrefix][$idxCoreProject];
                                                        }
                                                        break;
                                                    case 2:
                                                        // echo 'a2222--';
                                                        // exit;
                                                        // chek jika data adalah IP4
                                                        if (filter_var($sourceIPOnly, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false) {
                                                            // echo 'a2222-2-- harus masuk sini karena ada merupakan ip';
                                                            // $startIPValue = ip2long($valIP['startIP']);
                                                            $startIPValue = ip2long(preg_replace('/\b0+(\d)/', '$1', $valIP['startIP']));
                                                            // $endIPValue = ip2long($valIP['endIP']);
                                                            $endIPValue = ip2long(preg_replace('/\b0+(\d)/', '$1', $valIP['endIP']));
                                                            // $SourceIPOnlyValue = ip2long($sourceIPOnly);
                                                            $SourceIPOnlyValue = ip2long(preg_replace('/\b0+(\d)/', '$1', $sourceIPOnly));
                                                            if ($SourceIPOnlyValue >= $startIPValue && $SourceIPOnlyValue <= $endIPValue) {
                                                                $idxCoreProject = $tempIdxCoreProject;
                                                                $idxCoreProjectIp = $indIP;
                                                                $idxCorePrefix = $this->_DataIDPrefix[$destNoCustPrefix][$idxCoreProject];
                                                            }
                                                        }
                                                        
                                                        break;
                                                    case 3:
                                                        // echo 'a33333--';
                                                        // exit;
                                                        $needle = "SIP";
                                                        // chek jika data ada sip akan dilakukan condisional di bawah
                                                        if (strpos($sourceIPOnly, $needle) !== false) {
                                                            // echo $sourceIPOnly;
                                                            // echo 'a2222-1-- harus masuk sini karena ada sip';
                                                            // print_r($valIP);
                                                            // exit;
                                                            $startIPNumber = $valIP['startIPNumber'];
                                                            $endIpNumber = $valIP['endIpNumber'];
                                                            // echo 'sourceIPOnly';
                                                            // print_r($sourceIPOnly);
                                                            // echo 'sourceIPOnly';
                                                            // echo 'startIPNumber' . $startIPNumber;
                                                            // echo 'endIpNumber' . $endIpNumber;
                                                            // $decimalSourceIPOnly = $this->extract_numbers('SIP/INDOTRADING');
                                                            $decimalSourceIPOnly = $this->extract_numbers($sourceIPOnly);
                                                            // echo '88';
                                                            // print_r($decimalSourceIPOnly);
                                                            // echo '88';
                                                            // exit;
                                                            if ($decimalSourceIPOnly >= $startIPNumber && $decimalSourceIPOnly <= $endIpNumber) {
                                                                $idxCoreProject = $tempIdxCoreProject;
                                                                $idxCoreProjectIp = $indIP;
                                                                $idxCorePrefix = $this->_DataIDPrefix[$destNoCustPrefix][$idxCoreProject];
                                                            }
                                                        }
                                                        break;
                                                }
                                            // }
                                        }
                                    }
                                }
                                break;
                            }
                            // echo "langsung sini";exit;
                        }
                    }
                    // echo "idxCoreProjectIp ==> ";
                    // echo $idxCoreProjectIp;
                    // echo "idxCorePrefix ==> ";
                    // echo $idxCorePrefix;
                    // echo "idxCoreProject ==> ";
                    // echo $idxCoreProject;exit;
                    
                        ////////////////////////////////////////////////////////////////////////////////////////////// MOVE START
                        
                            ////////////////////////////////////////////////////////////////////////////////////////////// destNoPrefix dan DestNoPrefixName
                            $take_data = array(5, 6, 4, 3, 2, 1);
                            $arraydestNoPrefix = [];
                            // print_r($this->_DataDestNoPrefixName_all);exit;
                            foreach ($take_data as $valueDestNoPrefix) {
                                $destNoPrefix = substr($destNo, 4, (int)$valueDestNoPrefix);
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
                            // print_r($arraydestNoPrefix);exit;

                            // Fungsi untuk memfilter elemen-elemen array yang memiliki nilai pada kedua kunci yang ditentukan
                            $filteredArraydestNoPrefix = array_filter($arraydestNoPrefix, function($item) {
                                return !empty($item['destNoPrefix']) && !empty($item['destNoPrefixName']);
                            });
                            foreach ($filteredArraydestNoPrefix as $key => $valueFilteredArraydestNoPrefix) {
                                $destNoPrefix = $valueFilteredArraydestNoPrefix['destNoPrefix'];
                                $destNoPrefixName = $valueFilteredArraydestNoPrefix['destNoPrefixName'];
                                if (isset($idxCoreProject) && isset($this->_DataCustomerPrice[$idxCoreProject][$destNoPrefixName])) {
                                    $destNoPrefix = $valueFilteredArraydestNoPrefix['destNoPrefix'];
                                    $destNoPrefixName = $valueFilteredArraydestNoPrefix['destNoPrefixName'];
                                    break;
                                }
                            }
                            // echo $destNoPrefix;exit;

                            //////////////////////////////////////////////////////////////////////////////////////////////  custTime
                            $custTime = Null;
                            if (!empty($call_data_server['elapsedTime']) && is_numeric($call_data_server['elapsedTime']) ) {
                                // echo 'masuk sini';exit;
                                $custTime = $call_data_server['elapsedTime'];
                                if (preg_match("/\bpremium\b/i", $destNoPrefixName) || $chekNo0211 == '0211') {
                                    // echo 'masuk premium';exit;
                                    $hasil = $custTime / 60;
                                    $hasil_bulat = ceil($hasil);
                                    $roundCustTimeData = $hasil_bulat * 60;
                                    $custTime = $roundCustTimeData;
                                }

                                //////////////////////////////////////////////////////////////////////////////////////////////// pengecekan cli
                                // $this->_DataProjectDetailsCLI = $getProjectDetailsCLI;
                                // $this->_DataProjectDetailsFreeTrial = $getProjectDetailsFreeTrial;
                                if(isset($this->_DataProjectDetailsCLI[$idxCoreProject])) {
                                    if($call_data_server['elapsedTime'] <= 60){
                                        $custTime = 60;
                                    }
                                }
                            }
                            
                            $custPrice = Null;
                            // Cek apakah idxCoreProject ada dalam _DataCustomerGroup
                            // if (isset($this->_DataProjectMember[$idxCoreProject])) {
                            //     // echo "_DataCustomerGroup";exit;
                            //     $idxCoreGroup = $this->_DataProjectMember[$idxCoreProject];
                                
                            //     // Cek apakah idxCoreProjectGroup ada dalam _DataCustomerGroupPrice
                            //     if (isset($this->_DataCustomerGroupPrice[$idxCoreGroup])) {
                            //         $idxCoreGroupPrices = $this->_DataCustomerGroupPrice[$idxCoreGroup];
                            //         $bigTime = 0;
                            //         $bigPrice = 0;
                            //         $bigIdxCoreGroupPrices = 0;
                            //         foreach ($idxCoreGroupPrices as $idxCoreGroupPrices => $valueGroupPrice) {
                            //             $tarifPerMenit = $valueGroupPrice['tarifPerMenit']; 
                            //             $startRange = $valueGroupPrice['startRange']; 
                            //             $endRange = $valueGroupPrice['endRange'];
                                        
                            //             if (!empty($custTime)) {
                            //                 // echo 'aaa';exit;
                            //                 if ($custTime >= $startRange && $custTime <= $endRange) {
                            //                     $custPrice = $tarifPerMenit;
                            //                     $idxCoreGroupPrice = $idxCoreGroupPrices;
                            //                 }
                            //             }
                                        
                            //             if ($endRange >= $bigTime) {
                            //                 $bigTime = $endRange; 
                            //                 $bigPrice = $tarifPerMenit; 
                            //                 $bigIdxCoreGroupPrices = $idxCoreGroupPrices;
                            //             }

                            //         }

                            //         if ($custTime >= $bigTime){
                            //             $custPrice = $bigPrice;
                            //             $idxCoreGroupPrice = $bigIdxCoreGroupPrices;
                            //         }
                            //     }
                            // }

                            // Jika custPrice masih null, coba ambil harga langsung dari _DataCustomerPrice
                            if ($custPrice == null && isset($this->_DataCustomerPrice[$idxCoreProject][$destNoPrefixName])) {
                                // echo '_DataCustomerPrice 123';exit;
                                $custPrice = $this->_DataCustomerPrice[$idxCoreProject][$destNoPrefixName];
                            }

                            // Jika custPrice masih null, coba ambil harga langsung dari _DataProjectPrice
                            if ($custPrice == null && isset($this->_DataProjectPrice[$idxCoreProject])) {
                                // echo '_DataProjectPrice';exit;
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
                                // echo '3';exit;
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

                            //////////////////////////////////////////////////////////////////////////////////////////////// pengecekan free trial
                            // biaya jadi noll

                        ////////////////////////////////////////////////////////////////////////////////////////////// MOVE END

                    // echo "idxCoreProjectIp ==> ";
                    // echo $idxCoreProjectIp;
                    // echo "idxCorePrefix ==> ";
                    // echo $idxCorePrefix;
                    // echo "idxCoreProject ==> ";
                    // echo $idxCoreProject;exit;
                    if(empty($idxCoreProjectIp) || empty($idxCorePrefix) || empty($idxCoreProject)) {


                        // print_r($call_data_server);exit;

                        // 'idxCore' => Str::uuid()->toString(),
                        $dataToInsertRepairCSV = array(
                            'idxCore' => $idxCore,

                            'fileName' => $valueDataResult->fileName,
                            'folderName' => $folderName,
                            'readyPath' => $valueDataResult->readyPath,
                            'resultPath' => $valueDataResult->resultPath,
                            'dateTimeCSVtoDB' => $valueDataResult->dateTimeCSVtoDB,
                            'lineNumber' => $valueDataResult->lineNumber,
                            'serverData' => $dataServer,
                            'jmlKol' => $valueDataResult->jmlKol,
                            'jmlKolPHP' => $jmlKolPHP,
                            'data' => $valueDataResult->data,

                            'datetime' => $call_data_server['datetime'],
                            'sourceNo' => $call_data_server['sourceNo'],
                            'sourceNoOut' => $call_data_server['sourceNoOut'],
                            'sourceIP' => $call_data_server['sourceIP'],
                            'elapsedTime' => $call_data_server['elapsedTime'],
                            'destNo' => $call_data_server['destNo'],
                            'destNoOut' => $call_data_server['destNoOut'],
                            'destIP' => $call_data_server['destIP'],
                            'destName' => $call_data_server['destName'],
                            'destIPOnly' => $call_data_server['destIPOnly'],
                            'sourceIPOnly' => $call_data_server['sourceIPOnly'],
                            
                            'destNoCust' => $destNoCust,
                            'destNoCustPrefix' => $destNoCustPrefix,
                            'idxCoreProject' => $idxCoreProject,
                            'idxCorePrefix' => $idxCorePrefix,
                            'idxCoreProjectIp' => $idxCoreProjectIp,
                            'idxCoreCustGroup' => $idxCoreCustGroup,
                            'idxCoreGroupPrice' => $idxCoreGroupPrice,
                            'custTime' => $custTime,
                            'custPrice' => $custPrice,
                            'telkomPrice' => $telkomPrice,
                            'destNoPrefix' => $destNoPrefix,
                            'destNoPrefixName' => $destNoPrefixName,
                            'reasonCode' => 0
                        );
                        $arrayDataToInsertRepairCSV[] = $dataToInsertRepairCSV;


                        $dataToUpdateFor = array(
                            'idxCore' => $idxCore,
                            'testingData' => 0
                        );
                        $dataToUpdate[] = $dataToUpdateFor;
                        
                    } else {
                        // print_r($valueDataResult);exit;
                        // print_r($call_data_server['datetime']);exit;
                        $dataToInsertCDR = array(
                            'idxCore' => $idxCore,
                            'fileName' => $valueDataResult->fileName,
                            'folderName' => $folderName,
                            'readyPath' => $valueDataResult->readyPath,
                            'resultPath' => $valueDataResult->resultPath,
                            'dateTimeCSVtoDB' => $valueDataResult->dateTimeCSVtoDB,
                            'lineNumber' => $valueDataResult->lineNumber,
                            'serverData' => $dataServer,
                            'jmlKol' => $valueDataResult->jmlKol,
                            'jmlKolPHP' => $jmlKolPHP,
                            'data' => $valueDataResult->data,
                            
                            'datetime' => $call_data_server['datetime'],
                            'sourceNo' => $call_data_server['sourceNo'],
                            'sourceNoOut' => $call_data_server['sourceNoOut'],
                            'sourceIP' => $call_data_server['sourceIP'],
                            'elapsedTime' => $call_data_server['elapsedTime'],
                            'destNo' => $call_data_server['destNo'],
                            'destNoOut' => $call_data_server['destNoOut'],
                            'destIP' => $call_data_server['destIP'],
                            'destName' => $call_data_server['destName'],
                            'destIPOnly' => $call_data_server['destIPOnly'],
                            'sourceIPOnly' => $call_data_server['sourceIPOnly'],
                            
                            'destNoCust' => $destNoCust,
                            'destNoCustPrefix' => $destNoCustPrefix,
                            'idxCoreProject' => $idxCoreProject,
                            'idxCorePrefix' => $idxCorePrefix,
                            'idxCoreProjectIp' => $idxCoreProjectIp,
                            'idxCoreCustGroup' => $idxCoreCustGroup,
                            'idxCoreGroupPrice' => $idxCoreGroupPrice,
                            'custTime' => $custTime,
                            'custPrice' => $custPrice,
                            'telkomPrice' => $telkomPrice,
                            'destNoPrefix' => $destNoPrefix,
                            'destNoPrefixName' => $destNoPrefixName,
                            'reasonCode' => 3
                        );
                        $arrayDataToInsertCDR[] = $dataToInsertCDR;
                        $dataToUpdateFor = array(
                            'idxCore' => $idxCore,
                            'testingData' => 3
                        );
                        $dataToUpdate[] = $dataToUpdateFor;
                    }
                }

            }
            // exit;
            // print_r($arrayDataToInsertCDR);exit;
            $data['dataToInsertCDR'] = $arrayDataToInsertCDR;
            $data['dataToInsertRepairCSV'] = $arrayDataToInsertRepairCSV;
            $data['dataToInsertNoCdr'] = $dataNoCdr;
            // print_r($data);
            // exit;
            // return $data;
            
            if (Cdr::insert($data['dataToInsertCDR']) && repaircsv::insert($data['dataToInsertRepairCSV']) && NoCdr::insert($data['dataToInsertNoCdr'])) {                               
                // $result = ParserCsv::upsert($dataToUpdate, ['idxCore'], [
                //     'testingData'
                // ]);
                
                // if ($result > 0) {
                //     $message = array(true, 'Process Successful', 'Data updated successfully.', 'nextload(\'Yes\')');
                // }else{
                //     $message = array(false, 'Process Fails', 'The data could not be updated.', '');
                // }
                // echo 'jangan dulu delete data';exit;
                if (ParserCsv::limit($limitDataParser)->delete()) {
                    // $this->update_cdr();
                }
                $message = array(true, 'Process Successful', 'Data updated successfully.', 'nextload(\'Yes\')');
            }else{
                $message = array(false, 'Process Fails', 'The data could not be updated.', '');
            }
            
        }else{
            $message = array(true, 'Process Successful', 'Data updated successfully.', 'nextload(\'no\')');
        }
        echo json_encode($message);
    }
    
}

