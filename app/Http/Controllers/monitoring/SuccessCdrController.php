<?php

namespace App\Http\Controllers\monitoring;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\check_folder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\str;
use DB;

class SuccessCdrController extends Controller
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
            return view('monitoring.folderfile.index', [
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
            $data_cond = $this->seriliaze_decode($extra_search);

            # Condition
            $cond = array();

            if (!empty($data_cond['keyword'])) {
                $cond[] = ['name', 'LIKE', '%' . $data_cond['keyword'] . '%'];
            }

            $data_result = check_folder::where($cond)->offset($offset)->limit($limit)->get();
            $data_count = check_folder::where($cond)->count();


            $rows = array();
            $no = $offset;

            foreach ($data_result as $value) {
                $id = $value->idx;
                
                $CorePath = str_replace('/var/www/bill_system', '...', $value->CorePath);
                $ParserPath = str_replace('/var/www/bill_system', '...', $value->ParserPath);
                $ResultPath = str_replace('/var/www/bill_system', '...', $value->ResultPath);
                
                $no++;
                $rows[] = array(
                    $no,
                    $value->FolderName,
                    $value->DateTime,
                    $CorePath,
                    $value->JumlahCoreFile,
                    $ParserPath,
                    $value->JumlahParserFile,
                    $ResultPath,
                    $value->JumlahResultFile

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

    private function seriliaze_decode($array = array())
    {

        $dec = array();

        if (is_array($array)) {
            foreach ($array as $value) {
                $dec[$value['name']] = $value['value'];
            }
        }

        return $dec;
    }
    private function parser_access_menu($_id_role = '')
    {
        $id_user = Auth::user()->id_role;
        $url = $this->_url_data;
        $query = "
            SELECT 
                `a`.`id_menu`,
                `a`.`id_role`,
                `a`.`view_otoritas_modul`,
                `a`.`insert_otoritas_modul`,
                `a`.`update_otoritas_modul`,
                `a`.`delete_otoritas_modul`,
                `a`.`export_otoritas_modul`,
                `a`.`import_otoritas_modul`,
                `a`.`data_otoritas_modul`
            FROM `otoritas_moduls` `a` 
            JOIN `moduls` `b` ON `a`.`id_menu` = `b`.`id` 
            WHERE 
                `a`.`id_role` = '{$id_user}' AND 
                `b`.`url` = '{$url}' ;
        ";
        $data  = DB::select($query);
        if ( !empty($data) ){
            $this->_access_menu = $data[0];
        }else{
            return false;

        }
    }
}
