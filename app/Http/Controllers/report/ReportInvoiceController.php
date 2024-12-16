<?php

namespace App\Http\Controllers\report;


use App\Http\Controllers\Controller;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Services\MyService;

use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectPrefixSrv;
use App\Models\Cdr;
use DB;

use App\Models\sum\ReportCdr as SumReportCdr; // use App\Models\sum\ReportCdr;
use App\Models\sum\ReportInvoice as SumReportInvoice; // use App\Models\sum\ReportInvoice;

use App\Exports\reportinvoice as ExportReportInvoice; // use App\Exports\reportinvoice;
use App\Exports\reportinvoicedetil; // use App\Exports\reportinvoice;

class ReportInvoiceController extends Controller
{
    
    private $_page_title = 'Report Invoice x Tarif Telkom';
    private $_url_data = 'reportinvoice';
    private $_access_menu;
    private $_myService;
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
            // $projects = Project::skip(0)->take(100)->get();
            $FolderNames = DB::connection('mysql_third')
                ->table('check_folder_file')
                ->select('FolderName')
                ->groupBy('FolderName')
                ->get();
            return view('report.reportinvoice.index', [
                'page_title' => $this->_page_title,
                'projects' => $projects,
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
            // print_r($pieces);exit;
            # Condition
            $cond = array();
            $ProjectAlias = '-';
            $sumFolderName = array();
            $formattedDate = '';
            $formattedDateEnd = '';
            if (isset($pieces[0])) {
                $idxCoreProject = $pieces[0];
                $ProjectAlias = $pieces[1];
            }
            
            if (!empty($pieces[2])) {
                $formattedDate = $pieces[2];
                // Mengonversi ke timestamp
                $formattedDate = strtotime($formattedDate);
                // Memformat kembali menjadi d/m/Y
                $formattedDate = date('Y-m-d', $formattedDate);
            }

            if (!empty($pieces[3])) {
                $formattedDateEnd = $pieces[3];
                // Mengonversi ke timestamp
                $formattedDateEnd = strtotime($formattedDateEnd);
                // Memformat kembali menjadi d/m/Y
                $formattedDateEnd = date('Y-m-d', $formattedDateEnd);
            }
            
            if (isset($pieces[4]) && !empty($pieces[4])) {
                $temp_sumFolderName = explode(",", $pieces[4]);
                foreach ($temp_sumFolderName as $key => $value) {
                    $sumFolderName[] = $value;
                }
            }
            
            $results = SumReportInvoice::selectRaw("
                    date,
                    idxCoreProject,
                    destNoPrefixName,
                    SUM(jmlWaktuReal) AS WaktuReal,
                    SUM(jmlWaktuTagih) AS Duration,
                    custPrice,
                    SUM(biayaTagih) AS TotalPrice,
                    tarifTelkom,
                    SUM(biayaTelkom) AS TotalTelkom
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
                ->when(!empty($idxCoreProject), function ($query) use ($idxCoreProject) {
                    return $query->where('idxCoreProject', $idxCoreProject);
                })
                ->when(!empty($sumFolderName), function ($query) use ($sumFolderName) {
                    return $query->whereIn('folderName', $sumFolderName);
                })
                ->groupBy('idxCoreProject', 'date', 'destNoPrefixName', 'custPrice', 'tarifTelkom')
                ->get();
                

            return view('report.reportinvoice.show_all', [
                'page_title' => $this->_page_title,
                'ProjectAlias' => $ProjectAlias,
                'dateStart' => $formattedDate,
                'dateEnd' => $formattedDateEnd,
                'urlData' => $urlData,
                'results' => $results,
                'import_otoritas_modul' => $this->_access_menu->import_otoritas_modul
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }

    public function load_by_date(Request $request)
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
            $formattedDateEnd = '';
            $m_formattedDateEnd = '';
            $temp_folderNames = '';
            $idxCoreProjects = array();
            $folderNames = array();

            foreach ($extra_search as $item) {
                if ($item['name'] === 'idxCoreProject[]') {
                    $idxCoreProjects[] = $item['value'];
                }

                if ($item['name'] === 'folderName[]') {
                    $folderNames[] = $item['value'];
                    $temp_folderNames .= $item['value'].',';
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
                    date,
                    idxCoreProject,
                    SUM(jmlCdr)  AS totalCdr,
                    SUM(jmlPrefix)  AS totalPrefix,
                    SUM(jmlWaktuReal)  AS totalWaktuReal,
                    SUM(jmlWaktuTagih)  AS totalWaktuTagih,
                    SUM(biayaTagih)  AS totalbiayaTagih,
                    MAX(tarifTelkom) AS tarifTelkom,
                    SUM(biayaTelkom)  AS totalbiayaTelkom,
                    SUM(penghematan)  AS totalPrefix
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
                ->groupBy('date','idxCorePrefix', 'idxCoreProject', 'sourceIPOnly', 'folderName')
                ->offset($offset)
                ->limit($limit)
                ->get();
                // ->toSql();
                // dd($data_result);

            $data_count = SumReportCdr::selectRaw("
                    date,
                    idxCoreProject,
                    SUM(jmlCdr)  AS totalCdr,
                    SUM(jmlPrefix)  AS totalPrefix,
                    SUM(jmlWaktuReal)  AS totalWaktuReal,
                    SUM(jmlWaktuTagih)  AS totalWaktuTagih,
                    SUM(biayaTagih)  AS totalbiayaTagih,
                    MAX(tarifTelkom) AS tarifTelkom,
                    SUM(biayaTelkom)  AS totalbiayaTelkom,
                    SUM(penghematan)  AS totalPrefix
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
                ->groupBy('date','idxCorePrefix', 'idxCoreProject', 'sourceIPOnly', 'folderName')
                ->get()
                ->count();

            $rows = array();
            $no = $offset;

            foreach ($data_result as $value) {

                $id = $value->idxCore;
                $sumIdxCoreProject = $value->idxCoreProject;
                if (isset($this->_DataProject[$sumIdxCoreProject])) {
                    $projectAlias = $this->_DataProject[$sumIdxCoreProject];
                }

                $tempUrl = "{$sumIdxCoreProject}~{$projectAlias}~{$m_formattedDate}~{$m_formattedDateEnd}~{$temp_folderNames}"; 
                $encryptedData = Crypt::encryptString($tempUrl);
                // URL dengan data terenkripsi
                $url = urlencode($encryptedData);

                $action = ""; 
                $action .= "<a href='javascript:void(0);' 
                    class='btn bg-color-orange btn-xs margin-right-5 text-white' 
                    id='mybutton-show-{$id}' 
                    data-breadcrumb='View' 
                    onclick='my_form.open(this.id)' 
                    data-module='reportinvoice' 
                    data-url='reportinvoice/{$url}/show_all' 
                    data-original-title='View' 
                    rel='tooltip'
                    data-placement='left'><i class='fa fa-eye'></i></a>";
                
                $biaya = abs($value->totalbiayaTagih);
                $biayaTelkom = abs($value->totalbiayaTelkom);
                $penghematan = $biayaTelkom - $biaya;
                
                if ($biayaTelkom != 0) {
                    $penghematanPercentage = ($penghematan / $biayaTelkom) * 100;
                    $showPenghematan = number_format($penghematanPercentage, 2) . '%';
                } else {
                    $showPenghematan = "-"; // Atau pesan lain yang sesuai dengan kebutuhan Anda
                }
                
                // print_r($value);exit;
                $no++;
                $rows[] = array(
                    $no,
                    $value->date, //Tanggal
                    $projectAlias,
                    $this->_myService->columns_align(number_format($value->totalWaktuReal, 0, ',', '.'), 'right'), // Waktu Real
                    $this->_myService->columns_align(number_format($value->totalWaktuTagih, 0, ',', '.'), 'right'), // Waktu Tagih
                    $this->_myService->columns_align(number_format($value->totalbiayaTagih, 2, ',', '.'), 'right'), // Biaya
                    $this->_myService->columns_align(number_format($value->tarifTelkom, 0, ',', '.'), 'right'), // Tarif Telkom
                    $this->_myService->columns_align(number_format($value->totalbiayaTelkom, 2, ',', '.'), 'right'), // Biaya Telkom
                    $this->_myService->columns_align($showPenghematan, 'right'), // penghematan
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
            $formattedDate = '';
            $formattedDateEnd = '';
            $formattedDateEnd = '';
            $m_formattedDateEnd = '';
            $temp_folderNames = '';
            $idxCoreProjects = array();
            $folderNames = array();

            foreach ($extra_search as $item) {
                if ($item['name'] === 'idxCoreProject[]') {
                    $idxCoreProjects[] = $item['value'];
                }

                if ($item['name'] === 'folderName[]') {
                    $folderNames[] = $item['value'];
                    $temp_folderNames .= $item['value'].',';
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
                    SUM(jmlWaktuReal)  AS totalWaktuReal,
                    SUM(jmlWaktuTagih)  AS totalWaktuTagih,
                    SUM(biayaTagih)  AS totalbiayaTagih,
                    MAX(tarifTelkom) AS tarifTelkom,
                    SUM(biayaTelkom)  AS totalbiayaTelkom,
                    SUM(penghematan)  AS totalPenghematan
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
                ->groupBy('idxCorePrefix', 'idxCoreProject', 'folderName')
                ->offset($offset)
                ->limit($limit)
                ->get();
                // ->toSql();
                // dd($data_result);

            $data_count = SumReportCdr::selectRaw("
                    idxCorePrefix,
                    idxCoreProject,
                    SUM(jmlWaktuReal)  AS totalWaktuReal,
                    SUM(jmlWaktuTagih)  AS totalWaktuTagih,
                    SUM(biayaTagih)  AS totalbiayaTagih,
                    MAX(tarifTelkom) AS tarifTelkom,
                    SUM(biayaTelkom)  AS totalbiayaTelkom,
                    SUM(penghematan)  AS totalPenghematan
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
                ->groupBy('idxCorePrefix', 'idxCoreProject', 'folderName')
                ->get()
                ->count();

            $rows = array();
            $no = $offset;

            foreach ($data_result as $value) {

                $id = $value->idxCore;
                $sumIdxCoreProject = $value->idxCoreProject;
                if (isset($this->_DataProject[$sumIdxCoreProject])) {
                    $projectAlias = $this->_DataProject[$sumIdxCoreProject];
                }

                if (isset($this->_DataProjectPrefixSrv[$value->idxCorePrefix])) {
                    $prefixNumber = $this->_DataProjectPrefixSrv[$value->idxCorePrefix];
                }

                $tempUrl = "{$sumIdxCoreProject}~{$projectAlias}~{$m_formattedDate}~{$m_formattedDateEnd}~{$temp_folderNames}"; 
                $encryptedData = Crypt::encryptString($tempUrl);
                // URL dengan data terenkripsi
                $url = urlencode($encryptedData);

                $action = ""; 
                $action .= "<a href='javascript:void(0);' 
                    class='btn bg-color-orange btn-xs margin-right-5 text-white' 
                    id='mybutton-show-{$id}' 
                    data-breadcrumb='View' 
                    onclick='my_form.open(this.id)' 
                    data-module='reportinvoice' 
                    data-url='reportinvoice/{$url}/show_all' 
                    data-original-title='View' 
                    rel='tooltip'
                    data-placement='left'><i class='fa fa-eye'></i></a>";
                
                $biaya = abs($value->totalbiayaTagih);
                $biayaTelkom = abs($value->totalbiayaTelkom);
                $penghematan = $biayaTelkom - $biaya;
                
                if ($biayaTelkom != 0) {
                    $penghematanPercentage = ($penghematan / $biayaTelkom) * 100;
                    $showPenghematan = number_format($penghematanPercentage, 2) . '%';
                } else {
                    $showPenghematan = "-"; // Atau pesan lain yang sesuai dengan kebutuhan Anda
                }
                
                $no++;
                $rows[] = array(
                    $no,
                    $prefixNumber,
                    $projectAlias,
                    $this->_myService->columns_align(number_format($value->totalWaktuReal, 0, ',', '.'), 'right'), // Waktu Real
                    $this->_myService->columns_align(number_format($value->totalWaktuTagih, 0, ',', '.'), 'right'), // Waktu Tagih
                    $this->_myService->columns_align(number_format($value->totalbiayaTagih, 2, ',', '.'), 'right'), // Biaya
                    $this->_myService->columns_align(number_format($value->tarifTelkom, 0, ',', '.'), 'right'), // Tarif Telkom
                    $this->_myService->columns_align(number_format($value->totalbiayaTelkom, 2, ',', '.'), 'right'), // Biaya Telkom
                    $this->_myService->columns_align($showPenghematan, 'right'), // penghematan
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
    
    public function download(string $urlData)
    {
        
        # Condition
        $cond = array();
        $idxDesktop = '';
        $pieces = '';
        $formattedDate = '';
        $formattedDateEnd = '';
        $formattedDatePrint = '';
        $formattedDateEndPrint = '';
        $idxCoreProjects = array();
        $folderNames = array();

        // spliting and decript data
        if($urlData != 'active'){
            $decryptString = Crypt::decryptString($urlData);
            $pieces = explode("~", $decryptString);
        }
        // print_r ($pieces);exit;

        if (!empty($pieces[0])) {
            $ftDateStart = $pieces[0];
            $dateObject = \DateTime::createFromFormat('Y-m-d', $ftDateStart);
        
            if ($dateObject) {
                $formattedDate = $dateObject->format('Y-m-d');
                $formattedDatePrint = $dateObject->format('d M Y');
            } else {
                // Handle invalid date format
                echo "Invalid date format.";
            }
        } else {
            $ftDateStart = date('01/m/Y'); // Incorrect format for createFromFormat('Y-m-d', ...)
            $dateObject = \DateTime::createFromFormat('d/m/Y', $ftDateStart); // Adjusted the format here
        
            if ($dateObject) {
                $formattedDate = $dateObject->format('Y-m-d');
                $formattedDatePrint = $dateObject->format('d M Y');
            } else {
                // Handle invalid date format
                echo "Invalid date format.";
            }

            $ftDateEnd = date('d/m/Y'); // Using 'd/m/Y' format
            $dateObject = \DateTime::createFromFormat('d/m/Y', $ftDateEnd); // Adjusted the format here
            if ($dateObject) {
                $formattedDateEnd = $dateObject->format('Y-m-d');
                $formattedDateEndPrint = $dateObject->format('d M Y');
            } else {
                // Handle invalid date format
                echo "Invalid date format.";
            }
        }
        
        if (!empty($pieces[1])) {
            $ftDateEnd = $pieces[1];
            $dateObject = \DateTime::createFromFormat('Y-m-d', $ftDateEnd);
        
            if ($dateObject) {
                $formattedDateEnd = $dateObject->format('Y-m-d');
                $formattedDateEndPrint = $dateObject->format('d M Y');
            } else {
                // Handle invalid date format
                echo "Invalid date format.";
            }
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

        if (!empty($pieces[8])) {
            $temp_folderNames = explode(",", $pieces[8]);
        
            foreach ($temp_folderNames as $key => $value) {
                $folderNames[] = $value;
            }
        }

        
        $cond = array();
        $results = SumReportInvoice::selectRaw("
                date,
                idxCoreProject,
                SUM(jmlCdr)  AS totalCdr,
                SUM(jmlPrefix)  AS totalPrefix,
                SUM(jmlWaktuReal)  AS totalWaktuReal,
                SUM(jmlWaktuTagih)  AS totalWaktuTagih,
                SUM(biayaTagih)  AS totalbiayaTagih,
                MAX(custPrice) AS custPrice,
                MAX(tarifTelkom) AS tarifTelkom,
                SUM(biayaTelkom)  AS totalbiayaTelkom,
                SUM(penghematan)  AS totalPrefix
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
            ->groupBy('date','idxCorePrefix', 'idxCoreProject', 'sourceIPOnly', 'folderName')
            ->get();
        // $results = SumReportInvoice::where($cond)->get();
        
        $data_result = $results->toArray();
        $array1 = array('a');
        $array2 = array('a');
        $array3 = array('a');
        $data_result = array_merge($array1, $array2, $array3, $data_result);
        
        $namaFile = 'reportinvoice';
        return Excel::download(new ExportReportInvoice($data_result, $idxDesktop, $formattedDatePrint, $formattedDateEndPrint, $this->_DataProject), $namaFile.'.xlsx');
        // return Excel::download(new reportinvoice($data_result), $namaFile.'.xlsx');
    }
    
    public function download_detil(string $urlData)
    {
        
        $decryptString = Crypt::decryptString($urlData);
        $pieces = explode("~", $decryptString);
        
        // print_r($pieces);exit;
        # Condition
        $cond = array();
        $projectAlias = '-';
        $sumFolderName = array();
        $formattedDate = '';
        $formattedDateEnd = '';
        $formattedDatePrint = '';
        $formattedDateEndPrint = '';
        if (isset($pieces[0])) {
            $idxCoreProject = $pieces[0];
            $projectAlias = $pieces[1];
        }
        
        if (!empty($pieces[2])) {
            $formattedDate = $pieces[2];
            $str_formattedDate = strtotime($formattedDate);
            $formattedDate = date('Y-m-d', $str_formattedDate);
            $formattedDatePrint = date('d M Y', $str_formattedDate);
        }

        if (!empty($pieces[3])) {
            $formattedDateEnd = $pieces[3];
            $str_formattedDateEnd = strtotime($formattedDateEnd);
            $formattedDateEnd = date('Y-m-d', $str_formattedDateEnd);
            $formattedDateEndPrint = date('d M Y', $str_formattedDateEnd);
        }
        
        if (isset($pieces[4]) && !empty($pieces[4])) {
            $temp_sumFolderName = explode(",", $pieces[4]);
            foreach ($temp_sumFolderName as $key => $value) {
                $sumFolderName[] = $value;
            }
        }
        
        $results = SumReportInvoice::selectRaw("
            date,
            idxCoreProject,
            destNoPrefixName,
            SUM(jmlCdr)  AS totalCdr,
            SUM(jmlPrefix)  AS totalPrefix,
            SUM(jmlWaktuReal)  AS totalWaktuReal,
            SUM(jmlWaktuTagih)  AS totalWaktuTagih,
            SUM(biayaTagih)  AS totalbiayaTagih,
            MAX(custPrice) AS custPrice,
            MAX(tarifTelkom) AS tarifTelkom,
            SUM(biayaTelkom)  AS totalbiayaTelkom,
            SUM(penghematan)  AS totalPrefix
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
        ->when(!empty($idxCoreProject), function ($query) use ($idxCoreProject) {
            return $query->where('idxCoreProject', $idxCoreProject);
        })
        ->when(!empty($sumFolderName), function ($query) use ($sumFolderName) {
            return $query->whereIn('folderName', $sumFolderName);
        })
        ->groupBy('idxCoreProject', 'date', 'destNoPrefixName', 'custPrice', 'tarifTelkom')
        ->get();


        $data_result = $results->toArray();
        $array1 = array('a');
        $array2 = array('a');
        $array3 = array('a');
        $data_result = array_merge($array1, $array2, $array3, $data_result);

        $namaFile =  date('dmY'). '-' . $projectAlias ;
        return Excel::download(new reportinvoicedetil($data_result, $projectAlias, $formattedDatePrint, $formattedDateEndPrint, $this->_DataProject), $namaFile.'.xlsx');
        
        
    }
}
