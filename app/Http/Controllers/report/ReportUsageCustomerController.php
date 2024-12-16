<?php

namespace App\Http\Controllers\report;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;

use App\Services\MyService;
use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectPrefixSrv;
use App\Models\Cdr;
use DB;


use App\Exports\reportusagecustomer as ExportReportUsageCustomer;
use App\Exports\reportusagecustomersummary as ExportReportUsageCustomerSummary;
use App\Models\sum\ReportUsageCustomer as SumReportUsageCustomer;
use App\Models\sum\ReportCdr as SumReportCdr;

class ReportUsageCustomerController extends Controller
{
    
    private $_page_title = 'Rekap Durasi Project';
    private $_url_data = 'reportusagecustomer';
    private $_access_menu;
    private $_myService;
    private $_DataCustomer;
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
        $this->getProject();
        $this->getCustomer();
        $this->getProjectPrefixSrv();
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->_access_menu = $this->_myService->parser_access_menu($this->_url_data);
            return $next($request);
        });
    }
    
    private function getProject()
    {
        $Projects = Project::get();
        $getDataProjects = array();
        foreach ($Projects as $key => $val) {
            $getDataProjects[$val->idxCore] = $val->projectAlias;
        }
        $this->_DataProject = $getDataProjects;
        
        return 'succsess';
    }

    private function getCustomer()
    {
        $Customers = Customer::get();
        $getDataCustomers = array();
        foreach ($Customers as $key => $val) {
            $getDataCustomers[$val->idxCore] = $val->nama;
        }
        $this->_DataCustomer = $getDataCustomers;
        
        return 'succsess';
    }

    
    private function getProjectPrefixSrv()
    {
        $ProjectPrefixSrvs = ProjectPrefixSrv::get();
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
            // $projects = Project::skip(0)->take(100)->get();
            $customers = Customer::all();
            $FolderNames = DB::connection('mysql_third')
                ->table('check_folder_file')
                ->select('FolderName')
                ->groupBy('FolderName')
                ->get();
            return view('report.reportusagecustomer.index', [
                'page_title' => $this->_page_title,
                'customers' => $customers,
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
            } else {
                $ftDateStart = date('01/m/Y');
                $formattedDate = \DateTime::createFromFormat('d/m/Y', $ftDateStart)->format('Y-m-d');
                $m_formattedDate = \DateTime::createFromFormat('d/m/Y', $ftDateStart)->format('Ymd'); // kebutuhan encryptString
            }
            
            if (!empty($data_cond['ftDateEnd'])) {
                $ftDateEnd = $data_cond['ftDateEnd'];
                $formattedDateEnd = \DateTime::createFromFormat('d/m/Y', $ftDateEnd)->format('Y-m-d');
                $m_formattedDateEnd = \DateTime::createFromFormat('d/m/Y', $ftDateEnd)->format('Ymd'); // kebutuhan encryptString
            }else{
                $ftDateEnd = date('d/m/Y'); 
                $formattedDateEnd = \DateTime::createFromFormat('d/m/Y', $ftDateEnd)->format('Y-m-d');
                $m_formattedDateEnd = \DateTime::createFromFormat('d/m/Y', $ftDateEnd)->format('Ymd'); // kebutuhan encryptString
            }
            
            $data_result = SumReportCdr::selectRaw("
                idxCorePrefix,
                idxCoreProject,
                date,
                SUM(CASE WHEN serverData = 'MERA' THEN jmlWaktuReal ELSE 0 END) AS jmlWaktuRealMERA,
                SUM(CASE WHEN serverData = 'ELASTIX' OR serverData = 'ASTERISK' THEN jmlWaktuReal ELSE 0 END) AS jmlWaktuRealDIRECT,
                SUM(CASE WHEN serverData = 'VOS' THEN jmlWaktuReal ELSE 0 END) AS jmlWaktuRealVOS,
                SUM(jmlWaktuReal) AS jmlServerData
            ")
            ->when(!empty($idxCoreProjects), function ($query) use ($idxCoreProjects) {
                return $query->whereIn('idxCoreProject', $idxCoreProjects);
            })
            ->when(!empty($folderNames), function ($query) use ($folderNames) {
                return $query->whereIn('folderName', $folderNames);
            })
            ->where(function($query) use ($formattedDate, $formattedDateEnd) {
                if (!empty($formattedDate)) {
                    if (!empty($formattedDateEnd)) {
                        $query->whereRaw("DATE_FORMAT(date, '%Y-%m-%d') Between '{$formattedDate}' and '{$formattedDateEnd}'");
                    } else {
                        $query->whereRaw("DATE_FORMAT(date, '%Y-%m-%d') = '{$formattedDate}'");
                    }
                }
            })
            ->where($cond)
            ->groupBy('idxCoreProject', 'idxCorePrefix', 'date' )
            ->offset($offset)
            ->limit($limit)
            ->get();
            // ->toSql();
            // dd($data_result);

            $data_count = SumReportCdr::selectRaw("
                idxCorePrefix,
                idxCoreProject,
                date,
                SUM(CASE WHEN serverData = 'MERA' THEN jmlWaktuReal ELSE 0 END) AS jmlWaktuRealMERA,
                SUM(CASE WHEN serverData = 'ELASTIX' OR serverData = 'ASTERISK' THEN jmlWaktuReal ELSE 0 END) AS jmlWaktuRealDIRECT,
                SUM(CASE WHEN serverData = 'VOS' THEN jmlWaktuReal ELSE 0 END) AS jmlWaktuRealVOS,
                SUM(jmlWaktuReal) AS jmlServerData
            ")
            ->when(!empty($idxCoreProjects), function ($query) use ($idxCoreProjects) {
                return $query->whereIn('idxCoreProject', $idxCoreProjects);
            })
            ->when(!empty($folderNames), function ($query) use ($folderNames) {
                return $query->whereIn('folderName', $folderNames);
            })
            ->where(function($query) use ($formattedDate, $formattedDateEnd) {
                if (!empty($formattedDate)) {
                    if (!empty($formattedDateEnd)) {
                        $query->whereRaw("DATE_FORMAT(date, '%Y-%m-%d') Between '{$formattedDate}' and '{$formattedDateEnd}'");
                    } else {
                        $query->whereRaw("DATE_FORMAT(date, '%Y-%m-%d') = '{$formattedDate}'");
                    }
                }
            })
            ->where($cond)
            ->groupBy('idxCoreProject', 'idxCorePrefix', 'date' )
            ->get()
            ->count();
            
            $rows = array();
            $no = $offset;
            foreach ($data_result as $value) {

                $no++;
                $projectAlias = '';
                $prefixNumber = '';
                if (isset($this->_DataProject[$value->idxCoreProject])) {
                    $projectAlias = $this->_DataProject[$value->idxCoreProject];
                }
                if (isset($this->_DataProjectPrefixSrv[$value->idxCorePrefix])) {
                    $prefixNumber = $this->_DataProjectPrefixSrv[$value->idxCorePrefix];
                }
                
                $rows[] = [
                    $no,
                    $prefixNumber,
                    $projectAlias, // Customer
                    $value->date, // Tanggal
                    $this->_myService->columns_align(number_format($value->jmlWaktuRealMERA, 0, ',', '.'), 'right'),
                    $this->_myService->columns_align(number_format($value->jmlWaktuRealVOS, 0, ',', '.'), 'right'),
                    $this->_myService->columns_align(number_format($value->jmlWaktuRealDIRECT, 0, ',', '.'), 'right'),
                    $this->_myService->columns_align(number_format($value->jmlServerData, 0, ',', '.'), 'right')
                ];
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

    public function download(Request $request)
    {
        
        # Condition
        $cond = array();
        $projectAlias = 'Rekap Durasi Project';
        $formattedDate = '';
        $formattedDateEnd = '';
        if (!empty($pieces[0])) {
            $idxCoreProject = $pieces[0];
            if (isset($this->_DataProject[$idxCoreProject])) {
                $projectAlias = $this->_DataProject[$idxCoreProject];
            }
            $cond[] = ['idxCoreProject', $idxCoreProject ];
        }
        
        if (!empty($pieces[1])) {
            $sumDate = $pieces[1];
            $sumDate = strtotime($sumDate);
            $sumDate = date('Y-m-d', $sumDate);
            $formattedDate = $sumDate; 
        }
        // }else{ echo 'data dari summary cdr harus memiliki tanggal. Silahkan hubungi developer atau programmer.'; exit; }
    
        if (!empty($pieces[2])) {
            $formattedDate = $pieces[2];
            $formattedDate = strtotime($formattedDate);
            $formattedDate = date('Y-m-d', $formattedDate);
        }
        
        if (!empty($pieces[3])) {
            $formattedDateEnd = $pieces[3];
            $formattedDateEnd = strtotime($formattedDateEnd);
            $formattedDateEnd = date('Y-m-d', $formattedDateEnd);
        }

        $results = SumReportUsageCustomer::where($cond)->get();
        
        $data_result = $results->toArray();
        $array1 = array('a');
        $array2 = array('a');
        $array3 = array('a');
        $data_result = array_merge($array1, $array2, $array3, $data_result);
        
        $namaFile = 'Report Durasi Harian';
        $namaFile =  date('dmY'). '-' . $namaFile ;
        return Excel::download(new ExportReportUsageCustomer($data_result, $projectAlias, $formattedDate, $formattedDateEnd, $this->_DataProjectPrefixSrv,$this->_DataProject), $namaFile.'.xlsx');
        
    }
    
    public function downloadSummary(Request $request)
    {
        
        # Condition
        $cond = array();
        $projectAlias = 'Rekap Durasi Project';
        $formattedDate = '';
        $formattedDateEnd = '';
        if (!empty($pieces[0])) {
            $idxCoreProject = $pieces[0];
            if (isset($this->_DataProject[$idxCoreProject])) {
                $projectAlias = $this->_DataProject[$idxCoreProject];
            }
            $cond[] = ['idxCoreProject', $idxCoreProject ];
        }
        
        if (!empty($pieces[1])) {
            $sumDate = $pieces[1];
            $sumDate = strtotime($sumDate);
            $sumDate = date('Y-m-d', $sumDate);
            $formattedDate = $sumDate; 
        }
        // }else{ echo 'data dari summary cdr harus memiliki tanggal. Silahkan hubungi developer atau programmer.'; exit; }
    
        if (!empty($pieces[2])) {
            $formattedDate = $pieces[2];
            $formattedDate = strtotime($formattedDate);
            $formattedDate = date('Y-m-d', $formattedDate);
        }
        
        if (!empty($pieces[3])) {
            $formattedDateEnd = $pieces[3];
            $formattedDateEnd = strtotime($formattedDateEnd);
            $formattedDateEnd = date('Y-m-d', $formattedDateEnd);
        }

        $results = SumReportCdr::selectRaw("
            idxCorePrefix,
            idxCoreProject,
            SUM(CASE WHEN serverData = 'MERA' THEN jmlWaktuReal ELSE 0 END) AS jmlWaktuRealMERA,
            SUM(CASE WHEN serverData = 'ELASTIX' OR serverData = 'ASTERISK' THEN jmlWaktuReal ELSE 0 END) AS jmlWaktuRealDIRECT,
            SUM(CASE WHEN serverData = 'VOS' THEN jmlWaktuReal ELSE 0 END) AS jmlWaktuRealVOS,
            SUM(jmlWaktuReal) AS jmlServerData
        ")
        ->groupBy('idxCoreProject', 'idxCorePrefix')
        ->get();
        
        $data_result = $results->toArray();
        $array1 = array('a');
        $array2 = array('a');
        $array3 = array('a');
        $data_result = array_merge($array1, $array2, $array3, $data_result);
        
        $namaFile = 'Report Summary Durasi';
        $namaFile =  date('dmY'). '-' . $namaFile ;
        return Excel::download(new ExportReportUsageCustomerSummary($data_result, $projectAlias, $formattedDate, $formattedDateEnd, $this->_DataProjectPrefixSrv,$this->_DataProject), $namaFile.'.xlsx');
        
    }
}
