<?php

namespace App\Http\Controllers\monitoring;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\monitoring\CheckFolderFile;
use App\Services\MyService;
use Illuminate\Support\Facades\Crypt;
use DB;

class FolderFileServerController extends Controller
{

    private $_page_title = 'Folder File Server';
    private $_url_data = 'folderfileserver';
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

            return view('monitoring.folderfileserver.index', [
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

            # Condition
            $cond = array();
            
            $folderNames = array();
            foreach ($extra_search as $item) {
                if ($item['name'] === 'folderName') {
                    $folderNames[] = $item['value'];
                }
            }

            $data_result = CheckFolderFile::where($cond)
                ->when(!empty($folderNames), function ($query) use ($folderNames) {
                return $query->whereIn('FolderName', $folderNames);
            })
            ->offset($offset)->limit($limit)->get();

            $data_count = CheckFolderFile::where($cond)
                ->when(!empty($folderNames), function ($query) use ($folderNames) {
                return $query->whereIn('FolderName', $folderNames);
            })->count();


            $rows = array();
            $no = $offset;

            foreach ($data_result as $value) {
                $id = $value->idx;
                $encryptedData = Crypt::encryptString($id);
                // URL dengan data terenkripsi
                $url = urlencode($encryptedData);
                
                $action = '<div class="text-align-center">';

                    $action .= "<a href='javascript:void(0);' 
                    class='btn bg-color-orange btn-xs margin-right-5 text-white' 
                    id='mybutton-show-{$id}' 
                    data-breadcrumb='View' 
                    onclick='my_form.open(this.id)' 
                    data-module='customer' 
                    data-url='folderfileserver/{$url}/show_all' 
                    data-original-title='View' 
                    rel='tooltip'
                    data-placement='left'><i class='fa fa-eye'></i></a>";
                    

                $action .= '</div>';

                
                // $directoryName = dirname($PathCoreName);
                // $baseName = basename($directoryName);
                // $baseName = preg_match('/\/([^\/]+)$/', $directoryName, $matches) ? $matches[1] : "No match found.";
                $directoryName = $value->PathCoreName;
                $baseName = basename($value->PathCoreName);
                // $baseName = preg_match('/\\\\([^\\\\]+)\\\\([^\\\\]+)\\\\([^\\\\]+)$/', $directoryName, $matches) ? $matches[2] : "No match found.";
                $no++;
                $rows[] = array(
                    $no,
                    $value->folderName,
                    $value->fileName,
                    $this->_myService->columns_align($value->sizeCore, 'right'),
                    $this->_myService->columns_align($value->sizeReady, 'right'),
                    $this->_myService->columns_align($value->sizeResult, 'right'),
                    $this->_myService->columns_align(number_format($value->jumlahResult, 0, ',', '.'), 'right'),
                    $value->StatusReadyResult,
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
