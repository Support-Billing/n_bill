<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Services\MyService;
use App\Models\Log;
use DB;

class LogController extends Controller
{
    
    private $_page_title = 'Log';
    private $_url_data = 'log';
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
    
    //
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            return view('monitoring.log.index', [
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
            $cond[] = ['action', '=', 'Parser CDR'];

            if (!empty($data_cond['keyword'])) {
                $cond[] = ['module', 'LIKE', '%' . $data_cond['keyword'] . '%'];
            }

            $data_result = log::where($cond)->offset($offset)->limit($limit)->get();
            $data_count = log::where($cond)->count();


            $rows = array();
            $no = $offset;

            foreach ($data_result as $value) {
                $id = $value->idx;

                $status = '<span class="label label-warning">Tidak Berhasil</span>';
                if ($value->status) {
                    # code...
                    $status = '<span class="label label-success">Berhasil</span>';
                }

                $no++;
                $rows[] = array(
                    $no,
                    $value->module,
                    $value->action,
                    $value->description,
                    $status
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
