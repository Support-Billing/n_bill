<?php

namespace App\Http\Controllers\report;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Services\MyService;
use App\Models\Cdr;
use App\Models\Project;
use App\Models\ProjectPrefixSrv;
use App\Models\Customer;
use App\Models\sum\ReportBiayaCustomer;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;
use DB;


use App\Exports\reportbiayaproject;
// use App\Models\sum\ReportCdr as SumReportCdr;
use App\Models\sum\ReportCdr as SumReportCdr;

class ReportBiayaProjectController extends Controller
{
    
    private $_page_title = 'Report Biaya Project';
    private $_url_data = 'reportbiayaproject';
    private $_access_menu;
    private $_myService;
    private $_DataProjectPrefixSrv = null;
    private $_DataProject = null;

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

    private function getProjectPrefixSrv()
    {
        $ProjectPrefixSrvs = ProjectPrefixSrv::get();
        $getProjectPrefixSrvs = array();
        foreach ($ProjectPrefixSrvs as $key => $val) {
            $getProjectPrefixSrvs[$val->idxCore] = $val->prefixNumber;
        }
        $this->_DataProjectPrefixSrv = $getProjectPrefixSrvs;
        
        return 'succsess';
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
    

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            // $distinctYears = Cdr::selectRa/w('DISTINCT YEAR(datetime) as tahun')->get();
            $customers = Customer::all();
            $projects = Project::all();
            // $projects = Project::skip(0)->take(100)->get();
            $FolderNames = DB::connection('mysql_third')
                ->table('check_folder_file')
                ->select('FolderName')
                ->groupBy('FolderName')
                ->get();
            return view('report.reportbiayaproject.index', [
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
            } else {
                $ftDateStart = date('01/m/Y');
                $formattedDate = \DateTime::createFromFormat('d/m/Y', $ftDateStart)->format('Y-m-d');

                $ftDateEnd = date('d/m/Y'); 
                $formattedDateEnd = \DateTime::createFromFormat('d/m/Y', $ftDateEnd)->format('Y-m-d');
            }
            
            if (!empty($data_cond['ftDateEnd'])) {
                $ftDateEnd = $data_cond['ftDateEnd'];
                $formattedDateEnd = \DateTime::createFromFormat('d/m/Y', $ftDateEnd)->format('Y-m-d');
            }

            $data_result = SumReportCdr::selectRaw("
                    idxCorePrefix,
                    idxCoreProject,
                    SUM(jmlWaktuReal) AS Sum_jmlWaktuReal,
                    SUM(jmlWaktuTagih) AS Sum_jmlWaktuTagih,
                    SUM(biayaTagih) AS Sum_biayaTagih
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
                ->when(!empty($folderNames), function ($query) use ($folderNames) {
                    return $query->whereIn('folderName', $folderNames);
                })
                ->where($cond)
                ->groupBy('idxCoreProject', 'idxCorePrefix')
                ->offset($offset)
                ->limit($limit)
                ->get();

            $data_count = SumReportCdr::selectRaw("
                    idxCoreProject,
                    SUM(jmlWaktuReal) AS Sum_jmlWaktuReal,
                    SUM(jmlWaktuTagih) AS Sum_jmlWaktuTagih,
                    SUM(biayaTagih) AS Sum_biayaTagih
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
                ->when(!empty($folderNames), function ($query) use ($folderNames) {
                    return $query->whereIn('folderName', $folderNames);
                })
                ->where($cond)
                ->groupBy('idxCoreProject', 'idxCorePrefix')
                ->get()
                ->count();

            $rows = array();
            $no = $offset;
            foreach ($data_result as $value) {
                
                $nama = '';
                $idxCoreProject = $value->idxCoreProject;
                $idxCorePrefix = $value->idxCorePrefix;
                
                if (isset($this->_DataProject[$idxCoreProject])) {
                    $nama = $this->_DataProject[$idxCoreProject];
                }
                
                if (isset($this->_DataProjectPrefixSrv[$idxCorePrefix])) {
                    $PrefixNumber = $this->_DataProjectPrefixSrv[$idxCorePrefix];
                }
                
                $no++;
                $rows[] = array(
                    $no,
                    $PrefixNumber,
                    $nama,
                    $this->_myService->columns_align(number_format($value->Sum_jmlWaktuReal, 0, ',', '.'), 'right'),
                    $this->_myService->columns_align(number_format($value->Sum_jmlWaktuTagih, 0, ',', '.'), 'right'),
                    $this->_myService->columns_align(number_format($value->Sum_biayaTagih, 0, ',', '.'), 'right')
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

    
    public function download(string $urlData)
    {
        
        // $decryptString = Crypt::decryptString($urlData);
        // $pieces = explode("~", $decryptString);
        
        # Condition
                $cond = array();
                $projectAlias = 'Report Biaya Project';
                $formattedDate = '';
                $formattedDateEnd = '';
                // if (!empty($pieces[0])) {
                //     $idxCoreProject = $pieces[0];
                //     if (isset($this->_DataProject[$idxCoreProject])) {
                //         $projectAlias = $this->_DataProject[$idxCoreProject];
                //     }
                //     $cond[] = ['idxCoreProject', $idxCoreProject ];
                // }
                
                // if (!empty($pieces[1])) {
                //     $sumDate = $pieces[1];
                //     $sumDate = strtotime($sumDate);
                //     $sumDate = date('Y-m-d', $sumDate);
                //     $formattedDate = $sumDate; 
                
                // }else{ echo 'data dari summary cdr harus memiliki tanggal. Silahkan hubungi developer atau programmer.'; exit; }
            
                // if (!empty($pieces[2])) {
                //     $formattedDate = $pieces[2];
                //     $formattedDate = strtotime($formattedDate);
                //     $formattedDate = date('Y-m-d', $formattedDate);
                // }
                
                // if (!empty($pieces[3])) {
                //     $formattedDateEnd = $pieces[3];
                //     $formattedDateEnd = strtotime($formattedDateEnd);
                //     $formattedDateEnd = date('Y-m-d', $formattedDateEnd);
                // }

        $results = ReportBiayaCustomer::selectRaw("
            idxCorePrefix,
            idxCoreProject,
            SUM(jmlWaktuReal) as Sum_jmlWaktuReal ,
            SUM(jmlWaktuTagih) as Sum_jmlWaktuTagih,
            SUM(biayaTagih) as Sum_biayaTagih,
            count(idxCorePrefix) as Sum_idxCorePrefix
        ")
        ->where($cond)
        ->where(function($query) use ($formattedDate, $formattedDateEnd) {
            if (!empty($formattedDate)) {
                if (!empty($formattedDateEnd)) {
                    $query->whereRaw("DATE_FORMAT(datetime, '%Y-%m-%d') Between '{$formattedDate}' and '{$formattedDateEnd}'")
                    ->whereNotNull('datetime');
                }else{
                    $query->whereRaw("DATE_FORMAT(datetime, '%Y-%m-%d') = '{$formattedDate}'")
                        ->whereNotNull('datetime');
                }
            }
        })
        // ->limit(10)
        ->groupBy('idxCorePrefix', 'idxCoreProject')
        ->get();

        $data_result = $results->toArray();
        $array1 = array('a');
        $array2 = array('a');
        $array3 = array('a');
        $data_result = array_merge($array1, $array2, $array3, $data_result);
        
        $namaFile = 'reportbiayaproject';
        return Excel::download(new reportbiayaproject($data_result, $projectAlias, $formattedDate, $formattedDateEnd, $this->_DataProjectPrefixSrv,$this->_DataProject ), $namaFile.'.xlsx');
        
        
    }
}
