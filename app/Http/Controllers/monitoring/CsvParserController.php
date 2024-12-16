<?php

namespace App\Http\Controllers\monitoring;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\monitoring\ParserCsv;
use App\Services\MyService;
use DB;

class CsvParserController extends Controller
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
            return view('monitoring.csvparser.index', [
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

            $data_result = ParserCsv::where($cond)->offset($offset)->limit($limit)->get();
            $data_count = ParserCsv::where($cond)->count();


            $rows = array();
            $no = $offset;

            foreach ($data_result as $value) {
                // print_r ($value);exit;
                $id = $value->idx;
               
                $directoryName = $value->readyPath;
                $baseName = preg_match('/\/([^\/]+)\/([^\/]+)$/', $directoryName, $matches) ? $matches[1] : "No match found.";
                // $baseName = preg_match('/\\\\([^\\\\]+)\\\\([^\\\\]+)\\\\([^\\\\]+)$/', $directoryName, $matches) ? $matches[2] : "No match found.";
                // print_r ($baseName);exit;
                $no++;
                $rows[] = array(
                    $no,
                    $baseName,
                    $value->fileName,
                    $value->dateTimeCSVtoDB,
                    $value->lineNumber,
                    $value->jmlKol
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
}
