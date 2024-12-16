<?php

namespace App\Http\Controllers\monitoring;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\str;
use Illuminate\Support\Facades\Crypt;

use DB;

use App\Services\MyService;
use App\Models\monitoring\check_folder;

class FolderServerController extends Controller
{
    
    private $_page_title = 'Folder Server';
    private $_url_data = 'folderserver';
    private $_id_role;
    private $_access_menu;
    private $_myService;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_myService = app(MyService::class);
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->_access_menu = $this->_myService->parser_access_menu($this->_url_data);
            return $next($request);
        });
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            $check_folder_file = DB::connection('mysql_third')
                ->table('check_folder_file')
                ->select('FolderName')
                ->groupBy('FolderName')
                ->get();

            return view('monitoring.folderserver.index', [
                'page_title' => $this->_page_title,
                'results' => $check_folder_file,
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
            
            // Mencari nilai folderName dari $data_cond
            // $folderNames = array_column(array_filter($data_cond, function ($item) {
            //     return $item['name'] === 'folderName';
            // }), 'value');
            $folderNames = array();
            foreach ($extra_search as $item) {
                if ($item['name'] === 'folderName') {
                    $folderNames[] = $item['value'];
                }
            }

            # Condition
            $cond = array();
            $results = DB::connection('mysql_third')
                ->table('check_folder_file')
                ->select('FolderName', DB::raw('COUNT(FileName) as jumlahFile'), DB::raw('SUM(jumlahResult) as JumlahDataRow'))
                ->where($cond)
                ->when(!empty($folderNames), function ($query) use ($folderNames) {
                    return $query->whereIn('FolderName', $folderNames);
                })
                ->groupBy('FolderName')
                ->offset($offset)
                ->limit($limit)
                ->get();
                
            $resultsCount = DB::connection('mysql_third')
                ->table('check_folder_file')
                ->select('FolderName', DB::raw('COUNT(FileName) as jumlahFile'), DB::raw('SUM(jumlahResult) as JumlahDataRow'))
                ->where($cond)
                ->when(!empty($folderNames), function ($query) use ($folderNames) {
                    return $query->whereIn('FolderName', $folderNames);
                })
                ->groupBy('FolderName')
                ->get();
            $data_count = $resultsCount->count();

            $rows = array();
            $no = $offset;

            foreach ($results as $value) {
                $no++;
                $FolderName = $value->FolderName;
                $encryptedData = Crypt::encryptString($FolderName);
                // URL dengan data terenkripsi
                $url = urlencode($encryptedData);
                
                $action = "<a href='javascript:void(0);' 
                    class='btn bg-color-orange btn-xs margin-right-5 text-white' 
                    id='mybutton-show-{$encryptedData}' 
                    data-breadcrumb='View' 
                    onclick='my_form.open(this.id)' 
                    data-module='folderserver' 
                    data-url='folderserver/{$url}/show_all' 
                    data-original-title='View' 
                    rel='tooltip'
                    data-placement='left'><i class='fa fa-eye'></i></a>";
                    
                $rows[] = array(
                    $no,
                    $value->FolderName,
                    $this->_myService->columns_align(number_format($value->jumlahFile, 0, ',', '.'), 'right'),
                    $this->_myService->columns_align(number_format($value->JumlahDataRow, 0, ',', '.'), 'right'),
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

    
    public function encript_url(Request $request)
    {
        
        if($request->ajax())
        {
            
            # Condition
            $cond = array();
            $idxCore = $request->input('idxCore');
            $serverType = $request->input('serverType');
            $ftDateStart = $request->input('ftDateStart');
            $ftDateEnd = $request->input('ftDateEnd');
            $formattedDate = '';
            $formattedDateEnd = '';

            if (!empty($ftDateStart)) {
                $dateString = $ftDateStart;
                $formattedDate = \DateTime::createFromFormat('d/m/Y', $dateString)->format('Y-m-d');
            }

            if (!empty($ftDateEnd)) {
                $dateString = $ftDateEnd;
                $formattedDateEnd = \DateTime::createFromFormat('d/m/Y', $dateString)->format('Y-m-d');
            }
            
            $tempUrl = "{$idxCore}~{$formattedDate}~{$formattedDateEnd}~{$serverType}";
            $encryptedData = Crypt::encryptString($tempUrl);
            // URL dengan data terenkripsi
            $url = urlencode($encryptedData);
            echo $url;
            
        }else{
            return redirect('./#dashboard');
        }

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
}
