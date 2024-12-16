<?php

namespace App\Http\Controllers\report;


use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\MyService;
// use App\Exports\CdrExport;


use App\Models\Cdr;
use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectPrefixSrv;
// use App\Models\monitoring\FilesCsv;
// use App\Models\monitoring\SettingParser;
// use App\Models\monitoring\FilesCsvRepairByParser;

use App\Exports\reportcdrdetil;
use App\Exports\reportcdr as ExportReportCdr; // use App\Exports\reportcdr;
use App\Models\sum\ReportCdr as SumReportCdr; // use App\Models\sum\ReportCdr;


use DB;

class ReportCdrController extends Controller
{

    private $_page_title = 'Report CDR';
    private $_url_data = 'reportcdr';
    private $_access_menu;
    private $_myService;
    private $_DataProject;
    private $_DataProjectPrefixSrv;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // echo 'asd';exit;
        $this->_myService = app(MyService::class);
        $this->middleware('auth');
        $this->getProject();
        $this->getProjectPrefixSrv();
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

    public function getProject() {
        $Projects = Project::get();
        $getProjects = array();
        foreach ($Projects as $key => $val) {
            $getProjects[$val->idxCore] = $val->projectAlias; 
        }
        $this->_DataProject = $getProjects;
        
        return 'succsess';
    }

    private function getProjectPrefixSrv()
    {
        $ProjectPrefixSrvs = ProjectPrefixSrv::orderBy('idxCoreProject', 'asc')->get();
        $getProjectPrefixSrvs = array();
        foreach ($ProjectPrefixSrvs as $key => $val) {
            $getProjectPrefixSrvs[$val->idxCore] = $val->prefixNumber;
        }
        $this->_DataProjectPrefixSrv = $getProjectPrefixSrvs;
        // print_r($this->_DataProjectPrefixSrv);exit;
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
            $projects = Project::all();
            // $projects = Project::where('statusData', 1)->get();
            // $projects = Project::skip(0)->take(100)->get();
            $FolderNames = DB::connection('mysql_third')
                ->table('check_folder_file')
                ->select('FolderName')
                ->groupBy('FolderName')
                ->get();
                
            // $ProjectPrefixSrvs = ProjectPrefixSrv::where('active', 1)->get();
            // $ProjectPrefixSrvs = ProjectPrefixSrv::distinct()->pluck('prefixNumber');
            $projectPrefixSrvs = ProjectPrefixSrv::select('prefixNumber')->groupBy('prefixNumber')->get();
            
            return view('report.reportcdr.index', [
                'page_title' => $this->_page_title,
                'projects' => $projects,
                'projectPrefixSrvs' => $projectPrefixSrvs,
                'foldernames' => $FolderNames,
                'import_otoritas_modul' => $this->_access_menu->import_otoritas_modul
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }

    
    public function show_all(string $urlData)
    {
        
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            
            $decryptString = Crypt::decryptString($urlData);
            $pieces = explode("~", $decryptString);
            
            # Condition
            $cond = array();
            $projectAlias = '';
            $formattedDate = '';
            $formattedDateEnd = '';
            
            if (!empty($pieces[0])) {
                $formattedDate = $pieces[0];
                // Mengonversi ke timestamp
                $formattedDate = strtotime($formattedDate);
                // Memformat kembali menjadi d/m/Y
                $formattedDate = date('Y-m-d', $formattedDate);
            }

            if (!empty($pieces[1])) {
                $formattedDateEnd = $pieces[1];
                // Mengonversi ke timestamp
                $formattedDateEnd = strtotime($formattedDateEnd);
                // Memformat kembali menjadi d/m/Y
                $formattedDateEnd = date('Y-m-d', $formattedDateEnd);
            }

            if (!empty($pieces[2])) {
                $idxCoreProject = $pieces[2];
                if (isset($this->_DataProject[$idxCoreProject])) {
                    $projectAlias = $this->_DataProject[$idxCoreProject];
                }
                $cond[] = ['idxCoreProject', $idxCoreProject ];
            } 

            if (!empty($pieces[3])) {
                $idxCorePrefix = $pieces[3];
                if (isset($this->_DataProject[$idxCorePrefix])) {
                    $projectAlias = $this->_DataProject[$idxCoreProject];
                }
                $cond[] = ['idxCorePrefix', $idxCorePrefix ];
            }
            
            if (!empty($pieces[4])) {
                $sumFolderName = $pieces[4];
            }
            
            if (!empty($pieces[5])) {
                $sumSourceIPOnly = $pieces[5];
            }
            
            
            $priceGroup = '';
            $query_get_idxCoreCustGroup = "SELECT idxCoreCustGroup FROM `dev_billsystem`.`projects` WHERE `idxCore` = '$idxCoreProject';";
            $idxCoreCustGroupResult = DB::select($query_get_idxCoreCustGroup);
            if (!empty($idxCoreCustGroupResult) && !empty($idxCoreCustGroupResult[0]->idxCoreCustGroup)) {

                $idxCoreCustGroup = $idxCoreCustGroupResult[0]->idxCoreCustGroup;
                // echo $idxCoreCustGroup;exit;
                
                $results = Cdr::selectRaw(" idxCoreCustGroup, ROUND(SUM(custTime) / 60, 2) as totalCustTime ")
                    ->where(function($query) use ($formattedDate, $formattedDateEnd) {
                        if (!empty($formattedDate)) {
                            if (!empty($formattedDateEnd)) {
                                $query->whereRaw("DATE_FORMAT(datetime, '%Y-%m-%d') Between '{$formattedDate}' and '{$formattedDateEnd}'");
                            } else {
                                $query->whereRaw("DATE_FORMAT(datetime, '%Y-%m-%d') = '{$formattedDate}'");
                            }
                        }
                    })
                    ->where('idxCoreCustGroup', $idxCoreCustGroup)
                    ->whereNotNull('idxCoreProject')
                    ->groupBy('idxCoreCustGroup')
                    ->get();
                    // print_r ($results);exit;

                // Mengakses totalCustTime dari hasil pertama
                if ($results->isNotEmpty()) {
                    $totalCustTime = $results->first()->totalCustTime;
                    $idxCoreCustGroup = $results->first()->idxCoreCustGroup;
                    $query_get_price = "
                        SELECT tarifPerMenit
                        FROM customer_group_prices
                        WHERE `idxCoreCustGroup` = '$idxCoreCustGroup'  AND 
                        $totalCustTime BETWEEN startRange AND endRange;
                    ";
                    
                    $priceResult = DB::select($query_get_price);
                    // Assuming you expect only one result
                    if (!empty($priceResult)) {
                        $priceGroup = $priceResult[0]->tarifPerMenit;
                    }
                } 
            }
            
            $SumReportCdrs = SumReportCdr::
                // whereRaw("DATE(date) = ?", [$formattedDate])
                where(function($query) use ($formattedDate, $formattedDateEnd) {
                    if (!empty($formattedDate)) {
                        if (!empty($formattedDateEnd)) {
                            $query->whereRaw("DATE_FORMAT(date, '%Y-%m-%d') Between '{$formattedDate}' and '{$formattedDateEnd}'");
                        } else {
                            $query->whereRaw("DATE_FORMAT(date, '%Y-%m-%d') = '{$formattedDate}'");
                        }
                    }
                })
                ->when(!empty($idxCoreProject), function ($query) use ($idxCoreProject) {
                    return $query->where('idxCoreProject', $idxCoreProject);
                })
                ->when(!empty($idxCorePrefix), function ($query) use ($idxCorePrefix) {
                    return $query->where('idxCorePrefix', $idxCorePrefix);
                })
                ->when(!empty($sumFolderName), function ($query) use ($sumFolderName) {
                    return $query->where('folderName', $sumFolderName);
                })
                ->when(!empty($sumSourceIPOnly), function ($query) use ($sumSourceIPOnly) {
                    return $query->where('sourceIPOnly', $sumSourceIPOnly);
                })
                ->get();
                // ->toSql();
            // dd($SumReportCdrs);

            if ($SumReportCdrs->isNotEmpty()) {
                // echo 'a';exit;
                $SumReportCdr = new \stdClass();
                $SumReportCdr->jmlWaktuReal = 0;
                $SumReportCdr->jmlWaktuTagih = 0;
                $SumReportCdr->biayaTagih = 0;
            
                foreach ($SumReportCdrs as $valueSumReportCdr) {
                    $SumReportCdr->jmlWaktuReal += $valueSumReportCdr->jmlWaktuReal;
                    $SumReportCdr->jmlWaktuTagih += $valueSumReportCdr->jmlWaktuTagih;
                    $SumReportCdr->biayaTagih += $valueSumReportCdr->biayaTagih;
                }
                if(!empty($priceGroup)){
                    $getMenit_custTimeCDR = $SumReportCdr->jmlWaktuTagih/60 ;
                    $SumReportCdr->biayaTagih = $getMenit_custTimeCDR * $priceGroup;
                }
                
            } else {
                // Tetap menggunakan objek default
                $SumReportCdr = new \stdClass();
                $SumReportCdr->jmlWaktuReal = 0;
                $SumReportCdr->jmlWaktuTagih = 0;
                $SumReportCdr->biayaTagih = 0;
                echo "Tidak ada data yang ditemukan.\n";
            }

            // print_r ($SumReportCdr);exit;
            $CdrResults = Cdr::selectRaw("
                    datetime,
                    sourceNoOut,
                    sourceIPOnly,
                    destNo,
                    elapsedTime,
                    custTime,
                    custPrice
                ")
                ->where(function($query) use ($formattedDate, $formattedDateEnd) {
                    if (!empty($formattedDate)) {
                        if (!empty($formattedDateEnd)) {
                            $query->whereRaw("DATE_FORMAT(datetime, '%Y-%m-%d') Between '{$formattedDate}' and '{$formattedDateEnd}'");
                        } else {
                            $query->whereRaw("DATE_FORMAT(datetime, '%Y-%m-%d') = '{$formattedDate}'");
                        }
                    }
                })
                ->when(!empty($idxCorePrefix), function ($query) use ($idxCorePrefix) {
                    return $query->where('idxCorePrefix', $idxCorePrefix);
                })
                ->when(!empty($idxCoreProject), function ($query) use ($idxCoreProject) {
                    return $query->where('idxCoreProject', $idxCoreProject);
                })
                ->when(!empty($sumFolderName), function ($query) use ($sumFolderName) {
                    return $query->where('folderName', $sumFolderName);
                })
                ->when(!empty($sumSourceIPOnly), function ($query) use ($sumSourceIPOnly) {
                    return $query->where('sourceIPOnly', $sumSourceIPOnly);
                })
                ->orderBy('datetime', 'asc')
                ->limit(1000) // Batasi hasil menjadi 1000 baris
                ->get();
                // ->toSql(); // Menghasilkan SQL

                // dd($CdrResults);
            
                // if(isset($this->_DataProjectDetailsCLI[$idxCoreProject])) {
                //     if($call_data_server['elapsedTime'] <= 60){
                //         $custTime = 60;
                //     }
                // }
                
            ///
            // echo 'cli';
            $this->getProjectDetail();
            if(isset($this->_DataProjectDetailsCLI[$idxCoreProject])) {
                // echo 'adasda';
                $_DataProjectDetailsCLI = $this->_DataProjectDetailsCLI[$idxCoreProject];
                // print_r ($_DataProjectDetailsCLI);
                // if($call_data_server['elapsedTime'] <= 60){
                //     $custTime = 60;
                // }
            }
            // exit;
            return view('report.reportcdr.show_all', [
                'page_title' => $this->_page_title,
                'ProjectAlias' => $projectAlias,
                'dateStart' => $formattedDate,
                'dateEnd' => $formattedDateEnd,
                'urlData' => $urlData,
                'results' => $CdrResults,
                'priceGroup' => $priceGroup,
                'resultSumReportCdr' => $SumReportCdr,
                '_DataProjectDetailsCLI' => $_DataProjectDetailsCLI,
                'import_otoritas_modul' => $this->_access_menu->import_otoritas_modul
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }
    
    public function load (Request $request)
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
            $formattedDate = '';
            $m_formattedDate = '';
            $formattedDateEnd = '';
            $m_formattedDateEnd = '';
            $idxCoreProjects = array();
            $idxCorePrefixs = array();
            $folderNames = array();
            
            foreach ($extra_search as $item) {
                if ($item['name'] === 'idxCoreProject[]') {
                    $idxCoreProjects[] = $item['value'];
                }
                if ($item['name'] === 'idxCorePrefix[]') {
                    $idxCorePrefixs[] = $item['value'];
                }
                if ($item['name'] === 'folderName[]') {
                    $folderNames[] = $item['value'];
                }
            }
            
            if (!empty($data_cond['ftDateStart'])) {
                $ftDateStart = $data_cond['ftDateStart'];
                $formattedDate = \DateTime::createFromFormat('d/m/Y', $ftDateStart)->format('Y-m-d');
                $m_formattedDate = \DateTime::createFromFormat('d/m/Y', $ftDateStart)->format('Ymd'); // kebutuhan encryptString
            } else {
                $ftDateStart = date('01/m/Y');
                $formattedDate = \DateTime::createFromFormat('d/m/Y', $ftDateStart)->format('Y-m-d');
                $m_formattedDate = \DateTime::createFromFormat('d/m/Y', $ftDateStart)->format('Ymd'); // kebutuhan encryptString

                $ftDateEnd = date('d/m/Y'); 
                $formattedDateEnd = \DateTime::createFromFormat('d/m/Y', $ftDateEnd)->format('Y-m-d');
                $m_formattedDateEnd = \DateTime::createFromFormat('d/m/Y', $ftDateEnd)->format('Ymd'); // kebutuhan encryptString
            }
            
            if (!empty($data_cond['ftDateEnd'])) {
                $ftDateEnd = $data_cond['ftDateEnd'];
                $formattedDateEnd = \DateTime::createFromFormat('d/m/Y', $ftDateEnd)->format('Y-m-d');
                $m_formattedDateEnd = \DateTime::createFromFormat('d/m/Y', $ftDateEnd)->format('Ymd'); // kebutuhan encryptString
            }

            $data_result = SumReportCdr::selectRaw("
                    idxCorePrefix,
                    idxCoreProject,
                    sourceIPOnly,
                    folderName,
                    SUM(jmlCdr)  AS totalCdr,
                    SUM(jmlWaktuReal) AS totalWaktuReal
                ")
                ->where(function($query) use ($formattedDate, $formattedDateEnd) {
                    if (!empty($formattedDate)) {
                        if (!empty($formattedDateEnd)) {
                            $query->whereRaw("DATE_FORMAT(date, '%Y-%m-%d') Between '{$formattedDate}' and '{$formattedDateEnd}'");
                        } else {
                            $query->whereRaw("DATE_FORMAT(date, '%Y-%m-%d') = '{$formattedDate}'");
                        }
                    }
                })
                ->when(!empty($idxCoreProjects), function ($query) use ($idxCoreProjects) {
                    return $query->whereIn('idxCoreProject', $idxCoreProjects);
                })
                ->when(!empty($idxCorePrefixs), function ($query) use ($idxCorePrefixs) {
                    return $query->whereIn('idxCorePrefix', $idxCorePrefixs);
                })
                ->when(!empty($folderNames), function ($query) use ($folderNames) {
                    return $query->whereIn('folderName', $folderNames);
                })
                ->where($cond)
                ->groupBy('idxCoreProject', 'idxCorePrefix', 'folderName', 'sourceIPOnly')
                ->offset($offset)
                ->limit($limit)
                ->get();

            $total_count = SumReportCdr::selectRaw("
                    idxCorePrefix,
                    idxCoreProject,
                    sourceIPOnly,
                    folderName,
                    SUM(jmlCdr)  AS totalCdr,
                    SUM(jmlWaktuReal) AS totalWaktuReal
                ")
                ->where(function($query) use ($formattedDate, $formattedDateEnd) {
                    if (!empty($formattedDate)) {
                        if (!empty($formattedDateEnd)) {
                            $query->whereRaw("DATE_FORMAT(date, '%Y-%m-%d') Between '{$formattedDate}' and '{$formattedDateEnd}'");
                        } else {
                            $query->whereRaw("DATE_FORMAT(date, '%Y-%m-%d') = '{$formattedDate}'");
                        }
                    }
                })
                ->when(!empty($idxCoreProjects), function ($query) use ($idxCoreProjects) {
                    return $query->whereIn('idxCoreProject', $idxCoreProjects);
                })
                ->when(!empty($idxCorePrefixs), function ($query) use ($idxCorePrefixs) {
                    return $query->whereIn('idxCorePrefix', $idxCorePrefixs);
                })
                ->when(!empty($folderNames), function ($query) use ($folderNames) {
                    return $query->whereIn('folderName', $folderNames);
                })
                ->where($cond)
                ->groupBy('idxCoreProject', 'idxCorePrefix', 'folderName', 'sourceIPOnly')
                ->get()
                ->count();

            $rows = array();
            $no = $offset;
            foreach ($data_result as $value) {
                
                $projectAlias = '';
                $formattedDate = '';
                $formattedDateEnd = '';
                $sumDate = $value->date;
                $sumIdxCorePrefix = $value->idxCorePrefix;
                $sumIdxCoreProject = $value->idxCoreProject;
                $sumSourceIPOnly = $value->sourceIPOnly;
                $sumFolderName = $value->folderName;
                
                if (isset($this->_DataProjectPrefixSrv[$value->idxCorePrefix])) {
                    $prefixNumber = $this->_DataProjectPrefixSrv[$value->idxCorePrefix];
                }

                if (isset($this->_DataProject[$sumIdxCoreProject])) {
                    $projectAlias = $this->_DataProject[$sumIdxCoreProject];
                }
                // echo $sumDate;exit;
                $timestamp = strtotime($sumDate); // Mengonversi tanggal menjadi timestamp
                $numeric_date = date("Ymd", $timestamp); // Memformat tanggal dalam format angka (misalnya: 20220510)
                $idGenerator = $numeric_date.$sumIdxCoreProject;
                $tempUrl = "{$m_formattedDate}~{$m_formattedDateEnd}~{$sumIdxCoreProject}~{$sumIdxCorePrefix}~{$sumFolderName}~{$sumSourceIPOnly}";
                // echo 'aa';exit;
                // echo $tempUrl;exit;
                $encryptedData = Crypt::encryptString($tempUrl);
                $url = urlencode($encryptedData);
                
                
                $action = ""; 
                $action .= "<a href='javascript:void(0);' 
                    class='btn bg-color-orange btn-xs margin-right-5 text-white' 
                    id='mybutton-show-{$idGenerator}' 
                    data-breadcrumb='View' 
                    onclick='my_form.open(this.id)' 
                    data-module='reportcdr' 
                    data-url='reportcdr/{$url}/show_all' 
                    data-original-title='View' 
                    rel='tooltip'
                    data-placement='left'><i class='fa fa-eye'></i></a>";

                $no++;
                $rows[] = array(
                    $no,
                    // $value->date,
                    $prefixNumber,
                    $projectAlias,
                    $value->sourceIPOnly,
                    $value->folderName,
                    $this->_myService->columns_align(number_format($value->totalWaktuReal, 0, ',', '.'), 'right'),
                    $value->totalCdr,
                    $action
                );
            }

            $data = array(
                "draw" => $draw,
                "recordsTotal" => $total_count,
                "recordsFiltered" => $total_count,
                "data" => $rows
            );

            echo json_encode($data);
            
        }else{
            return redirect('./#dashboard');
        }
    }
    
    public function encript_url (Request $request)
    {
        
        if($request->ajax())
        {
            
            # Condition
            $SformattedDate = '';
            $SformattedDateEnd = '';
            $formattedDateEnd = '';
            $implodeidxCoreCustGroup = '';
            $implodeidxCoreCust = '';
            $implodeIdxCoreProject = '';
            $implodeidxCorePrefix = '';
            $implodefolderName = '';
            $cond = array();
            $ftDateStart = $request->input('ftDateStart');
            $ftDateEnd = $request->input('ftDateEnd');
            $SftDateStart = $request->input('SftDateStart');
            $SftDateEnd = $request->input('SftDateEnd');
            $folderName = $request->input('folderName');
            $serverData = $request->input('serverData');
            $sourceIPOnly = $request->input('sourceIPOnly');
            $idxCoreCustGroup = $request->input('idxCoreCustGroup');
            $idxCoreCust = $request->input('idxCoreCust');
            $idxCoreProject = $request->input('idxCoreProject');
            $idxCorePrefix = $request->input('idxCorePrefix');


            if (!empty($ftDateStart)) {
                $dateString = $ftDateStart;
                $formattedDate = \DateTime::createFromFormat('d/m/Y', $dateString)->format('Y-m-d');
            }else{
                $dateString = date('01/m/Y');
                $formattedDate = \DateTime::createFromFormat('d/m/Y', $dateString)->format('Y-m-d');
                
                $dateString = date('d/m/Y'); 
                $formattedDateEnd = \DateTime::createFromFormat('d/m/Y', $dateString)->format('Y-m-d');
            }

            if (!empty($ftDateEnd)) {
                $dateString = $ftDateEnd;
                $formattedDateEnd = \DateTime::createFromFormat('d/m/Y', $dateString)->format('Y-m-d');
            }
            
            if (!empty($SftDateStart)) {
                $dateString = $SftDateStart;
                $SformattedDate = \DateTime::createFromFormat('d/m/Y', $dateString)->format('Y-m-d');
            }

            if (!empty($SftDateEnd)) {
                $dateString = $SftDateEnd;
                $SformattedDateEnd = \DateTime::createFromFormat('d/m/Y', $dateString)->format('Y-m-d');
            }

            if (!empty($idxCoreCustGroup) && is_array($idxCoreCustGroup)) {
                $implodeidxCoreCustGroup = implode(',', $idxCoreCustGroup);
            }

            if (!empty($idxCoreCust) && is_array($idxCoreCust)) {
                $implodeidxCoreCust = implode(',', $idxCoreCust);
            }

            if (!empty($idxCoreProject) && is_array($idxCoreProject)) {
                $implodeIdxCoreProject = implode(',', $idxCoreProject);
            }

            if (!empty($idxCorePrefix) && is_array($idxCorePrefix)) {
                $implodeidxCorePrefix = implode(',', $idxCorePrefix);
            }

            if (!empty($folderName) && is_array($folderName)) {
                $implodefolderName = implode(',', $folderName);
            }

            $tempUrl = "{$formattedDate}~{$formattedDateEnd}~{$SformattedDate}~{$SformattedDateEnd}~{$implodeidxCoreCustGroup}~{$implodeidxCoreCust}~{$implodeIdxCoreProject}~{$implodeidxCorePrefix}~{$implodefolderName}~{$serverData}";
            $encryptedData = Crypt::encryptString($tempUrl);
            // 0~{$formattedDate}
            // 1~{$formattedDateEnd}
            // 2~{$SformattedDate}
            // 3~{$SformattedDateEnd}
            // 4~{$implodeidxCoreCustGroup}
            // 5~{$implodeidxCoreCust}
            // 6~{$implodeIdxCoreProject}
            // 7~{$implodeidxCorePrefix}
            // 8~{$implodefolderName}
            // 9~{$serverData}
            // URL dengan data terenkripsi
            $url = urlencode($encryptedData);
            echo $url;
            
        }else{
            return redirect('./#dashboard');
        }

    }
    

    public function download (string $urlData)
    {
        
        if($urlData != 'active'){
            $decryptString = Crypt::decryptString($urlData);
            $pieces = explode("~", $decryptString);
        }
        
        # Condition
        $cond = array();
        $idxCoreProjects = array();
        $projectAlias = '';
        $_DataProject = $this->_DataProject;
        $_DataProjectPrefixSrv = $this->_DataProjectPrefixSrv;
        $formattedDate = '';
        $formattedDateEnd = '';
        $namaFileDownload = '';
        $idxCoreProjects = array();
        $idxCorePrefixs = array();
        
        if (isset($pieces[0])) {
            $formattedDate = $pieces[0];
            $namaFileDownload .= $formattedDate;  // Penggabungan string dengan operator titik (= .)
        }else{
            $formattedDate = date('Y-m-01');
        }
        
        if (isset($pieces[1])) {
            $formattedDateEnd = $pieces[1];
            $namaFileDownload .= '_sd_'. $formattedDateEnd;  // Penggabungan string dengan operator titik (= .)
        }else{
            $formattedDateEnd = date('Y-m-d');
        }

        if (!empty($pieces[6])) {
            $temp_idxCoreProjects = explode(",", $pieces[6]);
            
            if (count($temp_idxCoreProjects) == 1) {
                $idxCoreProject = $temp_idxCoreProjects[0];
                $projectAlias = $this->_DataProject[$idxCoreProject] ?? "Kode ID Project : $idxCoreProject , tidak memiliki relasi";
            }
        
            foreach ($temp_idxCoreProjects as $key => $value) {
                $idxCoreProjects[] = $value;
            }
        }

        if (!empty($pieces[7])) {
            $temp_idxCorePrefixs = explode(",", $pieces[7]);
            foreach ($temp_idxCorePrefixs as $key => $value) {
                $idxCorePrefixs[] = $value;
            }
        }

        if (!empty($pieces[8])) {
            $temp_folderNames = explode(",", $pieces[8]);
            foreach ($temp_folderNames as $value) {
                $folderNames[] = $value;
            }
        }
        
        if (empty($projectAlias)) {
            $namaFileDownload = 'Summary_Days_CDR';
        }else{
            $namaFileDownload = $projectAlias;
        }

        $results = SumReportCdr::selectRaw("
            idxCorePrefix,
            idxCoreProject,
            sourceIPOnly,
            folderName,
            SUM(jmlCdr)  AS totalCdr,
            SUM(jmlWaktuReal) AS totalWaktuReal,
            SUM(jmlWaktuTagih) AS totalWaktuTagih,
            SUM(biayaTagih) AS totalBiayaTagih
        ")
        ->when(!empty($idxCoreProjects), function ($query) use ($idxCoreProjects) {
            return $query->whereIn('idxCoreProject', $idxCoreProjects);
        })
        ->when(!empty($idxCorePrefixs), function ($query) use ($idxCorePrefixs) {
            return $query->whereIn('idxCorePrefix', $idxCorePrefixs);
        })
        ->where(function($query) use ($formattedDate, $formattedDateEnd) {
            if (!empty($formattedDate)) {
                if (!empty($formattedDateEnd)) {
                    $query->where(function($subQuery) use ($formattedDate, $formattedDateEnd) {
                        $subQuery->whereRaw("DATE_FORMAT(date, '%Y-%m-%d') Between ? and ?", [$formattedDate, $formattedDateEnd])
                                ->orWhereNull('date');
                    });
                } else {
                    $query->where(function($subQuery) use ($formattedDate) {
                        $subQuery->whereRaw("DATE_FORMAT(date, '%Y-%m-%d') = ?", [$formattedDate])
                                ->orWhereNull('date');
                    });
                }
            }
        })
        ->groupBy('idxCorePrefix', 'idxCoreProject', 'sourceIPOnly', 'folderName')
        ->get();
        
        $data_result = $results->toArray();
        $array1 = array('a');
        $array2 = array('a');
        $array3 = array('a');
        $data_result = array_merge($array1, $array2, $array3, $data_result);
        // print_r($_DataProjectPrefixSrv);exit;
        return Excel::download(new ExportReportCdr($data_result, $projectAlias, $formattedDate, $formattedDateEnd, $_DataProject, $_DataProjectPrefixSrv), date('dmY').'-Summary CDR per IP dan Server.xlsx');
        
    }

    public function get_prefix(Request $request)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            $idxCoreProjects = $request->post('project');

            $ProjectPrefixSrvs = ProjectPrefixSrv::when(!empty($idxCoreProjects), function ($query) use ($idxCoreProjects) {
                return $query->whereIn('idxCoreProject', $idxCoreProjects);
            })->orderBy('idxCoreProject', 'asc')->get();

            return view('report.reportcdr.prefixSelect', [
                'ProjectPrefixSrvs' => $ProjectPrefixSrvs
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }

    public function download_detil(string $urlData)
    {
        
        $decryptString = Crypt::decryptString($urlData);
        $pieces = explode("~", $decryptString);
        
        # Condition
        $cond = array();
        $projectAlias = '';
        $formattedDate = '';
        $formattedDateEnd = '';
        
        if (!empty($pieces[0])) {
            $formattedDate = $pieces[0];
            $formattedDate = strtotime($formattedDate);
            $formattedDate = date('Y-m-d', $formattedDate);
        }

        if (!empty($pieces[1])) {
            $formattedDateEnd = $pieces[1];
            $formattedDateEnd = strtotime($formattedDateEnd);
            $formattedDateEnd = date('Y-m-d', $formattedDateEnd);
        }

        if (!empty($pieces[2])) {
            $idxCoreProject = $pieces[2];
            if (isset($this->_DataProject[$idxCoreProject])) {
                $projectAlias = $this->_DataProject[$idxCoreProject];
            }
            $cond[] = ['idxCoreProject', $idxCoreProject ];
        } 

        if (!empty($pieces[3])) {
            $idxCorePrefix = $pieces[3];
            if (isset($this->_DataProject[$idxCorePrefix])) {
                $projectAlias = $this->_DataProject[$idxCoreProject];
            }
            $cond[] = ['idxCorePrefix', $idxCorePrefix ];
        }
        
        if (!empty($pieces[4])) {
            $sumFolderName = $pieces[4];
        }
        
        if (!empty($pieces[5])) {
            $sumSourceIPOnly = $pieces[5];
        }
        
        
        $priceGroup = '';
        $query_get_idxCoreCustGroup = " SELECT idxCoreCustGroup FROM `dev_billsystem`.`projects` WHERE `idxCore` = '$idxCoreProject';";
        $idxCoreCustGroupResult = DB::select($query_get_idxCoreCustGroup);
        if (!empty($idxCoreCustGroupResult) && !empty($idxCoreCustGroupResult[0]->idxCoreCustGroup)) {
            $idxCoreCustGroup = $idxCoreCustGroupResult[0]->idxCoreCustGroup;
            // echo $idxCoreCustGroup;exit;
            
            $results = Cdr::selectRaw(" idxCoreCustGroup, ROUND(SUM(custTime) / 60, 2) as totalCustTime ")
                ->where(function($query) use ($formattedDate, $formattedDateEnd) {
                    if (!empty($formattedDate)) {
                        if (!empty($formattedDateEnd)) {
                            $query->whereRaw("DATE_FORMAT(datetime, '%Y-%m-%d') Between '{$formattedDate}' and '{$formattedDateEnd}'");
                        } else {
                            $query->whereRaw("DATE_FORMAT(datetime, '%Y-%m-%d') = '{$formattedDate}'");
                        }
                    }
                })
                ->where('idxCoreCustGroup', $idxCoreCustGroup)
                ->whereNotNull('idxCoreProject')
                ->groupBy('idxCoreCustGroup')
                ->get();

            // Mengakses totalCustTime dari hasil pertama
            if ($results->isNotEmpty()) {
                $totalCustTime = $results->first()->totalCustTime;
                $idxCoreCustGroup = $results->first()->idxCoreCustGroup;
                $query_get_price = "
                    SELECT tarifPerMenit
                    FROM customer_group_prices
                    WHERE `idxCoreCustGroup` = '$idxCoreCustGroup'  AND 
                    $totalCustTime BETWEEN startRange AND endRange;
                ";
                
                $priceResult = DB::select($query_get_price);
                // Assuming you expect only one result
                if (!empty($priceResult)) {
                    $priceGroup = $priceResult[0]->tarifPerMenit;
                }
            } 
        }

        $results = Cdr::selectRaw("
                DATE_FORMAT(datetime, '%d/%m/%Y') AS tanggal,
                TIME_FORMAT(datetime, '%H:%i:%s') AS jam,
                CASE WHEN sourceNo = 'NODID' THEN sourceNoOut ELSE sourceNo END AS sourceNoOut,
                sourceIPOnly AS IP,
                destNo,
                elapsedTime AS WaktuReal,
                custTime AS Duration,
                custPrice AS custprice,
                (custTime/60) * custPrice AS TotalPrice
            ")
            ->where(function($query) use ($formattedDate, $formattedDateEnd) {
                if (!empty($formattedDate)) {
                    if (!empty($formattedDateEnd)) {
                        $query->whereRaw("DATE_FORMAT(datetime, '%Y-%m-%d') Between '{$formattedDate}' and '{$formattedDateEnd}'");
                    } else {
                        $query->whereRaw("DATE_FORMAT(datetime, '%Y-%m-%d') = '{$formattedDate}'");
                    }
                }
            })
            ->where('idxCoreProject', $idxCoreProject)
            ->whereNotNull('idxCoreProject')
            ->orderBy('datetime', 'asc')
            ->get();


        $data_result = $results->toArray();
        $array1 = array('a');
        $array2 = array('a');
        $array3 = array('a');
        $data_result = array_merge($array1, $array2, $array3, $data_result);
        
        // $namaFile = 'reportcdr';
        $namaFile =  date('dmY'). '-' . $projectAlias ;
        return Excel::download(new reportcdrdetil($data_result, $projectAlias, $formattedDate, $formattedDateEnd, $priceGroup), $namaFile.'.xlsx');
        
    }
        
}
