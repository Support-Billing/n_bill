<?php

namespace App\Http\Controllers\monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\MyService;
use App\Models\Cdr;
use DB;

class CdrController extends Controller
{
    
    private $_page_title = 'CDR';
    private $_url_data = 'cdr';
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
        return view('monitoring.cdr.index', [
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

            $data_result = Cdr::where($cond)->offset($offset)->limit($limit)->get();
            $data_count = Cdr::where($cond)->count();


            $rows = array();
            $no = $offset;

            foreach ($data_result as $value) {
                $id = $value->idxCore ;
                $linkdelete =  url('cdr/'.$id);

                $action = "<a href='javascript:void(0);' 
                    class='btn bg-color-orange btn-xs margin-right-5 text-white' 
                    id='mybutton-show-{$id}' 
                    data-breadcrumb='View' 
                    onclick='my_form.open(this.id)' 
                    data-module='customer' 
                    data-url='cdr/{$id}/show_all' 
                    data-original-title='View' 
                    rel='tooltip'
                    data-placement='left'><i class='fa fa-eye'></i></a>";

                $no++;
                $rows[] = array(
                    $no,
                    $value->fileName,
                    $value->dateTimeCSVtoDB,
                    $value->lineNumber,
                    $value->elapsedTime,
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

}
