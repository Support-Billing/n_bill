<?php

namespace App\Http\Controllers\report;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\MyService;

use App\Exports\reportcdrdetil;

use App\Models\Cdr;
use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectPrefixSrv;
use App\Models\sum\ReportCdr as SumReportCdr; // use App\Models\sum\ReportCdr;
use App\Exports\reportcompareduration as ExportCompareDuration; // use App\Exports\reportcdr;

use App\Models\sum\ReportUsageCompareDurasi; 


use DB;

class ReportCompareDurationController extends Controller
{

    private $_page_title = 'Report Perbandingan CDR';
    private $_url_data = 'reportcompareduration';
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
        $this->_myService = app(MyService::class);
        $this->middleware('auth');
        $this->getProject();
        $this->getProjectPrefixSrv();
        $this->middleware(function ($request, $next) {
            $this->_access_menu = $this->_myService->parser_access_menu($this->_url_data);
            return $next($request);
        });
    }

    public function getProject() {
        $Projects = Project::get();
        // $Projects = Project::skip(0)->take(3)->get();
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
            // $projects = Project::skip(0)->take(3)->get();
            $FolderNames = DB::connection('mysql_third')
                ->table('check_folder_file')
                ->select('FolderName')
                ->groupBy('FolderName')
                ->get();
            return view('report.reportcompareduration.index', [
                'page_title' => $this->_page_title,
                'projects' => $projects,
                'foldernames' => $FolderNames,
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
            $formattedDateEnd = '';
            $sformattedDate = '';
            $sformattedDateEnd = '';
            $idxCoreProjects = array();
            $folderNames = array();
            
            foreach ($extra_search as $item) {
                if ($item['name'] === 'idxCoreProject[]') {
                    $idxCoreProjects[] = $item['value'];
                }
                if ($item['name'] === 'folderName[]') {
                    $folderNames[] = $item['value'];
                }
            }
            
            if (!empty($data_cond['ftDateStart'])) {
                $ftDateStart = $data_cond['ftDateStart'];
                $formattedDate = \DateTime::createFromFormat('d/m/Y', $ftDateStart)->format('Y-m-d');
                $m_formattedDate = \DateTime::createFromFormat('d/m/Y', $ftDateStart)->format('Ymd'); // kebutuhan encryptString
            }else{
                $ftDateStart = date('01/m/Y');
                $formattedDate = \DateTime::createFromFormat('d/m/Y', $ftDateStart)->format('Y-m-d');
                $m_formattedDate = \DateTime::createFromFormat('d/m/Y', $ftDateStart)->format('Ymd'); // kebutuhan encryptString
            }
            
            if (!empty($data_cond['ftDateEnd'])) {
                $ftDateEnd = $data_cond['ftDateEnd'];
                $formattedDateEnd = \DateTime::createFromFormat('d/m/Y', $ftDateEnd)->format('Y-m-d');
                $m_formattedDateEnd = \DateTime::createFromFormat('d/m/Y', $ftDateEnd)->format('Ymd');// kebutuhan encryptString
            }else{
                $ftDateEnd = date('d/m/Y'); 
                $formattedDateEnd = \DateTime::createFromFormat('d/m/Y', $ftDateEnd)->format('Y-m-d');
                $m_formattedDateEnd = \DateTime::createFromFormat('d/m/Y', $ftDateEnd)->format('Ymd');// kebutuhan encryptString
            }

            if (!empty($data_cond['SftDateStart'])) {
                $SftDateStart = $data_cond['SftDateStart'];
                $sformattedDate = \DateTime::createFromFormat('d/m/Y', $SftDateStart)->format('Y-m-d');
                $sm_formattedDate = \DateTime::createFromFormat('d/m/Y', $SftDateStart)->format('Ymd'); // kebutuhan encryptString
            }else{
                // Ambil tanggal 01 pada bulan yang berjalan
                $SftDateStart = date('01/m/Y', strtotime('-1 month'));
                $sformattedDate = \DateTime::createFromFormat('d/m/Y', $SftDateStart)->format('Y-m-d');
                $sm_formattedDate = \DateTime::createFromFormat('d/m/Y', $ftDateStart)->format('Ymd'); // kebutuhan encryptString
            }
            
            if (!empty($data_cond['SftDateEnd'])) {
                $SftDateEnd = $data_cond['SftDateEnd'];
                $sformattedDateEnd = \DateTime::createFromFormat('d/m/Y', $SftDateEnd)->format('Y-m-d');
                $sm_formattedDateEnd = \DateTime::createFromFormat('d/m/Y', $SftDateEnd)->format('Ymd');// kebutuhan encryptString
            }else{
                $SftDateEnd = date('t/m/Y', strtotime('-1 month'));
                $sformattedDateEnd = \DateTime::createFromFormat('d/m/Y', $SftDateEnd)->format('Y-m-d');
                $sm_formattedDateEnd = \DateTime::createFromFormat('d/m/Y', $ftDateEnd)->format('Ymd');// kebutuhan encryptString
            }
            
            $data_result = SumReportCdr::select(
                'idxCorePrefix',
                'idxCoreProject',
                DB::raw("SUM(CASE 
                            WHEN DATE(date) BETWEEN ? AND ? THEN jmlWaktuReal
                            ELSE 0 
                        END) AS First_Date_Period"),
                DB::raw("SUM(CASE 
                            WHEN DATE(date) BETWEEN ? AND ? THEN jmlWaktuReal
                            ELSE 0 
                        END) AS Second_Date_Period")
            )
            ->addBinding([$formattedDate, $formattedDateEnd])
            ->addBinding([$sformattedDate, $sformattedDateEnd])
            ->when(!empty($idxCoreProjects), function ($query) use ($idxCoreProjects) {
                return $query->whereIn('idxCoreProject', $idxCoreProjects);
            })
            ->when(!empty($folderNames), function ($query) use ($folderNames) {
                return $query->whereIn('folderName', $folderNames);
            })
            ->groupBy('idxCorePrefix', 'idxCoreProject')
            ->offset($offset)
            ->limit($limit)
            ->get();
            // ->toSql();
            // dd($data_result);


            $data_count = SumReportCdr::select(
                'idxCorePrefix',
                'idxCoreProject',
                DB::raw("SUM(CASE 
                            WHEN DATE(date) BETWEEN ? AND ? THEN jmlWaktuReal
                            ELSE 0 
                        END) AS First_Date_Period"),
                DB::raw("SUM(CASE 
                            WHEN DATE(date) BETWEEN ? AND ? THEN jmlWaktuReal
                            ELSE 0 
                        END) AS Second_Date_Period")
            )
            ->addBinding([$formattedDate, $formattedDateEnd])
            ->addBinding([$sformattedDate, $sformattedDateEnd])
            ->when(!empty($idxCoreProjects), function ($query) use ($idxCoreProjects) {
                return $query->whereIn('idxCoreProject', $idxCoreProjects);
            })
            ->when(!empty($folderNames), function ($query) use ($folderNames) {
                return $query->whereIn('folderName', $folderNames);
            })
            ->groupBy('idxCorePrefix', 'idxCoreProject')
            ->get();

            // Hitung jumlah grup dalam hasil query
            $data_count = $data_count->count();
            
            $rows = array();
            $no = $offset;
            foreach ($data_result as $value) {
                
                $projectAlias = '';
                $formattedDate = '';
                $formattedDateEnd = '';
                $sumDate = $value->date;
                $sumIdxCoreProject = $value->idxCoreProject;
                
                if (isset($this->_DataProject[$sumIdxCoreProject])) {
                    $projectAlias = $this->_DataProject[$sumIdxCoreProject];
                }

                $prefixNumber = '';
                if (isset($this->_DataProjectPrefixSrv[$value->idxCorePrefix])) {
                    $prefixNumber = $this->_DataProjectPrefixSrv[$value->idxCorePrefix];
                }

                $timestamp = strtotime($sumDate); // Mengonversi tanggal menjadi timestamp
                $numeric_date = date("Ymd", $timestamp); // Memformat tanggal dalam format angka (misalnya: 20220510)
                $idGenerator = $numeric_date.$sumIdxCoreProject;
                $tempUrl = "{$sumIdxCoreProject}~{$numeric_date}~{$m_formattedDate}~{$m_formattedDateEnd}";
                $encryptedData = Crypt::encryptString($tempUrl);
                $url = urlencode($encryptedData);
                
                $First_Date_Period = number_format($value->First_Date_Period, 0, ',', '.');
                $Second_Date_Period = number_format($value->Second_Date_Period, 0, ',', '.');
                
                $no++;
                $rows[] = array(
                    $no,
                    $prefixNumber,
                    $projectAlias,
                    $this->_myService->columns_align($First_Date_Period, 'right'),
                    $this->_myService->columns_align($Second_Date_Period, 'right')
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
    public function download (string $urlData)
    {
        
        if($urlData != 'active'){
            $decryptString = Crypt::decryptString($urlData);
            $pieces = explode("~", $decryptString);
        }
        // print_r ($pieces);exit;
        
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
        
        if (isset($pieces[0])) {
            $formattedDate = $pieces[0];
        }else{
            $formattedDate = date('Y-m-01');
        }
        

        if (isset($pieces[1])) {
            $formattedDateEnd = $pieces[1];
        }else{
            $formattedDateEnd = date('Y-m-d');
        }
        
        if (isset($pieces[2])) {
            $SformattedDate = $pieces[2];
        }else{
            $SformattedDate = date('Y-m-01', strtotime('-1 month'));
        }
        
        if (isset($pieces[3])) {
            $SformattedDateEnd = $pieces[3];
        }else{
            $SformattedDateEnd = date('Y-m-t', strtotime('-1 month'));
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
        
        
        if (empty($projectAlias)) {
            $namaFileDownload = 'Summary_Days_CDR';
        }else{
            $namaFileDownload = $projectAlias;
        }

        $results = SumReportCdr::select(
            'idxCorePrefix',
            'idxCoreProject',
            DB::raw("SUM(CASE 
                        WHEN DATE(date) BETWEEN ? AND ? THEN jmlWaktuReal
                        ELSE 0 
                    END) AS First_Date_Period"),
            DB::raw("SUM(CASE 
                        WHEN DATE(date) BETWEEN ? AND ? THEN jmlWaktuReal
                        ELSE 0 
                    END) AS Second_Date_Period")
        )
        ->addBinding([$formattedDate, $formattedDateEnd])
        ->addBinding([$SformattedDate, $SformattedDateEnd])
        ->when(!empty($idxCoreProjects), function ($query) use ($idxCoreProjects) {
            return $query->whereIn('idxCoreProject', $idxCoreProjects);
        })
        ->groupBy('idxCorePrefix', 'idxCoreProject')
        ->get();
        
        
        $data_result = $results->toArray();
        $array1 = array('a');
        $array2 = array('a');
        $array3 = array('a');
        $data_result = array_merge($array1, $array2, $array3, $data_result);
        
        $_dateRange = 'First Date Period ' . $formattedDate .' s/d ' . $formattedDateEnd;
        $_dateRangeSecond = 'Second Date Period ' . $SformattedDate .' s/d '. $SformattedDateEnd;
        
        $formattedDate . $formattedDateEnd;
        return Excel::download(new ExportCompareDuration($data_result, 'Report Compare Duration', $_dateRange, $_dateRangeSecond, $_DataProjectPrefixSrv, $_DataProject), date('dmY_His').'-Report Compare Duration.xlsx');
        
    }
        
}
