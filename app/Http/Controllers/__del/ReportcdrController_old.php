<?php

namespace App\Http\Controllers\report;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\cdr;
use DB;

class ReportcdrController_old extends Controller
{

    private $_access_menu;
    private $_page_title = 'Report CDR';
    private $_url_data = 'reportcdr';
    private $_id_role = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $_id_role = Auth::user()->id_role;
            $this->parser_access_menu($_id_role);
    
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
            return view('report.reportcdr.index', [
                'page_title' => $this->_page_title,
                'import_otoritas_modul' => $this->_access_menu->import_otoritas_modul
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }
    
   public function generator_folder(string $idx)
   {
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

            $data_result = cdr::where($cond)->offset($offset)->limit($limit)->get();
            $data_count = cdr::where($cond)->count();


            $rows = array();
            $no = $offset;

            foreach ($data_result as $value) {

                $no++;
                $rows[] = array(
                    $no,
                    $value->filename,
                    $value->idxCustomer,
                    $value->idxSupplier,
                    $value->idxCustomerIP,
                    $value->idxCustomerIPPrefix,
                    $value->idxSupplierIP,
                    $value->idxSupplierIPPrefix,
                    $value->idxServer                
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
