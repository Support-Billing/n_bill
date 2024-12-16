<?php

namespace App\Http\Controllers\exreport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Services\MyService;
use DB;

class ExreportNewIpController extends Controller
{
    
    private $_page_title = 'Executive View New IP';
    private $_url_data = 'exreportnewip';
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
            return view('content.customer.index', [
                'page_title' => $this->_page_title,
                'insert_otoritas_modul' => $this->_access_menu->insert_otoritas_modul
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

            $data_result = bank::where($cond)->offset($offset)->limit($limit)->get();
            $data_count = bank::where($cond)->count();


            $rows = array();
            $no = $offset;

            foreach ($data_result as $value) {
                $id = $value->bankID;
                $linkdelete =  url('bank/'.$id);

                $action = '<div class="text-align-center">';

                if($this->_access_menu->update_otoritas_modul){
                    $action .= "<a href='javascript:void(0);' 
                        class='btn btn-warning btn-xs margin-right-5' 
                        id='mybutton-edit-{$id}' 
                        data-breadcrumb='Edit' 
                        onclick='my_form.open(this.id)' 
                        data-module='bank' 
                        data-url='bank/{$id}/edit' 
                        data-original-title='Edit' 
                        rel='tooltip'
                        data-placement='left'><i class='fa fa-edit'></i></a>";

                    $action .= "<a 
                        href='javascript:void(0);'
                        class='btn btn-success btn-xs margin-right-5' 
                        id='mybutton-change-{$id}'
                        onclick='my_data_table.row_action_change.ajax(this.id)'
                        data-original-title='Change Status' 
                        rel='tooltip'
                        data-url='bank/{$id}/change_status'
                        ><i class='fa fa-exchange'></i></a>";
                }

                if($this->_access_menu->delete_otoritas_modul){
                    $action .= "<a 
                        href='javascript:void(0);'
                        class='btn btn-danger btn-xs' 
                        id='mybutton-delete-{$id}'
                        onclick='my_data_table.row_action.ajax(this.id)'
                        data-original-title='Delete' 
                        rel='tooltip' 
                        data-url='{$linkdelete}'
                        ><i class='fa fa-trash-o'></i></a>";
                }

                $action .= '</div>';

                $statusData = '<span class="label label-warning">Inactive</span>';
                if ($value->statusData) {
                    # code...
                    $statusData = '<span class="label label-success">Active</span>';
                }

                $no++;
                $rows[] = array(
                    $no,
                    $value->bankName,
                    $value->bankAcc,
                    $value->bankCode,
                    $value->bankAddress,
                    $value->accName,
                    $statusData,
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
