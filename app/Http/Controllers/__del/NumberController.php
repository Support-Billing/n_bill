<?php

namespace App\Http\Controllers;

use App\Models\Number;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\str;
use DB;

class NumberController extends Controller
{

    private $_access_menu;
    private $_page_title = 'Number';
    private $_url_data = 'number';
    private $_id_role = '';
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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
            return view('content.number.index', [
                'page_title' => $this->_page_title,
                'insert_otoritas_modul' => $this->_access_menu->insert_otoritas_modul
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            return view('content.number.create', [
                'page_title' => $this->_page_title
            ]);
        }else{
            // return abort(404);
            return redirect('./#dashboard');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request->ajax())
        {
            $validation = Validator::make($request->all(), [
                "name" => ['required', 'min:3', 'max:255'],
            ]);

            if ($validation->fails()) {
                $message = array(false, 'Process Fails', $validation->errors(), '');
            }else{
                
                $name = $request->get('name');

                $newData = new number;
                $newData->name = $name;
                // $newData->created_by = Auth::user()->id;
                if ($newData->save()){
                    $message = array(true, 'Process Successfully', 'The data has been saved.', 'my_form.reset(\'#finput\')');
                }else{
                    $message = array(false, 'Process Fails', 'The data could not be saved.', '');
                }
            }

            echo json_encode($message);
        }else{
            return redirect('./#dashboard');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            $number = number::findOrFail($id);
            return view('content.number.edit', [
                'page_title' => $this->_page_title,
                'data' => $number
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        if($request->ajax())
        {
            $updateData = number::findOrFail($id);
            $validation = Validator::make($request->all(), [
                "name" => ['required', 'min:3', 'max:255'],
                "address" => ['required', 'min:3', 'max:255'],
                "phone" => ['required', 'min:3', 'max:255']
            ]);
            if ($validation->fails()) {
                $message = array(false, 'Process Fails', $validation->errors(), '');
            }else{
                $message = array('error', 'Error!', 'An error occurred during storage , please try again.', '');
    
                $name = $request->get('name');
                $address = $request->get('address');
                $phone = $request->get('phone');
    
                $updateData->name = $name;
                $updateData->phone = $phone;
                $updateData->address = $address;
                // $newData->created_by = Auth::user()->id;
                if ($updateData->save()){
                    $message = array(true, 'Process Successful', 'Data updated successfully.', '');
                }else{
                    $message = array(false, 'Process Fails', 'The data could not be updated.', '');
                }
            }
    
            echo json_encode($message);
        }else{
            return redirect('./#dashboard');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($work_location)
    {
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            $del = number::findOrFail($work_location);
            if(!empty($del)){
                if ($del->delete()){
                    $message = array(true, 'Process Successfully', 'The data has been Deleted.', 'my_data_table.reload(\'#dt_basic\')');
                }else{
                    $message = array(false, 'Process Fails', 'Data can\'t Deleted.', '');
                }
            }else{
                    $message = array(false, 'Process Fails', 'Data can\'t Deleted.', '');
    
            }
            echo json_encode($message);
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

            $data_result = number::where($cond)->offset($offset)->limit($limit)->get();
            $data_count = number::where($cond)->count();


            $rows = array();
            $no = $offset;

            foreach ($data_result as $value) {
                $id = $value->id;
                $linkdelete =  url('number/'.$id);

                $action = '<div class="text-align-center">';

                $action .= "<a href='javascript:void(0);' 
                    class='btn btn-warning btn-xs margin-right-5' 
                    id='mybutton-edit-{$id}' 
                    data-breadcrumb='Edit' 
                    onclick='my_form.open(this.id)' 
                    data-module='number' 
                    data-url='number/{$id}/edit' 
                    data-original-title='Edit' 
                    rel='tooltip'
                    data-placement='left'><i class='fa fa-edit'></i></a>";

                $action .= "<a 
                    href='javascript:void(0);'
                    class='btn btn-danger btn-xs' 
                    id='mybutton-delete-{$id}'
                    onclick='my_data_table.row_action.ajax(this.id)'
                    data-original-title='Delete' 
                    rel='tooltip' 
                    data-url='{$linkdelete}'
                    ><i class='fa fa-trash-o'></i></a>";

                $action .= '</div>';

                $no++;
                $rows[] = array(
                    $no,
                    $value->name,
                    $value->phone,
                    $value->address,
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
        // echo $query;exit;
        $data  = DB::select($query);
        if ( !empty($data) ){
            $this->_access_menu = $data[0];
            // return json_decode(json_encode($data[0]));
            // return $data;
        }else{
            return false;

        }
    }
}
