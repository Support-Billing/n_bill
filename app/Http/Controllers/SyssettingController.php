<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\str;

use App\Services\MyService;
use App\Models\SysSetting;
use DB;

class SysSettingController extends Controller
{
    
    private $_page_title = 'System Setting';
    private $_url_data = 'syssetting';
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
            return view('content.syssetting.index', [
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
            return view('content.syssetting.create', [
                'page_title' => $this->_page_title
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }

    /*
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request->ajax())
        {
            //
            $validation = Validator::make($request->all(), [
                "key" => ['required', 'min:3', 'max:255'],
                "name" => ['required', 'min:3', 'max:255'],
                "value" => ['required', 'min:3', 'max:255'],
                "description" => ['required', 'min:3', 'max:255']
            ]);

            if ($validation->fails()) {
                $message = array(false, 'Process Fails', $validation->errors(), '');
            }else{
                
                $key = $request->get('key');
                $name = $request->get('name');
                $value = $request->get('value');
                $description = $request->get('description');

                $newData = new SysSetting;
                $newData->id = str::Uuid(36);
                $newData->key = $key;
                $newData->name = $name;
                $newData->value = $value;
                $newData->description = $description;
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
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            $syssetting = SysSetting::findOrFail($id);

            return view('content.syssetting.edit', [
                'page_title' => 'Work Location',
                'data' => $syssetting
            ]);
        }else{
            // return abort(404);
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
            $updateData = SysSetting::findOrFail($id);
            $validation = Validator::make($request->all(), [
                "key" => ['required', 'min:3', 'max:255'],
                "name" => ['required', 'min:3', 'max:255'],
                "value" => ['required', 'min:3', 'max:255'],
                "description" => ['required', 'min:3', 'max:255']
            ]);
            if ($validation->fails()) {
                $message = array(false, 'Process Fails', $validation->errors(), '');
            }else{
                $message = array('error', 'Error!', 'An error occurred during storage , please try again.', '');

                $key = $request->get('key');
                $name = $request->get('name');
                $value = $request->get('value');
                $description = $request->get('description');

                $updateData->key = $key;
                $updateData->name = $name;
                $updateData->value = $value;
                $updateData->description = $description;
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
    public function destroy($id)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            $del = SysSetting::findOrFail($id);
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
        if($request->ajax()){

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

            $data_result = SysSetting::where($cond)->offset($offset)->limit($limit)->get();
            $data_count = SysSetting::where($cond)->count();


            $rows = array();
            $no = $offset;

            foreach ($data_result as $value) {
                $id = $value->id;
                $linkdelete =  url('syssetting/'.$id);

                $action = '<div class="text-align-center">';
                
                $action .= "<a href='javascript:void(0);' 
                    class='btn btn-warning btn-xs margin-right-5' 
                    id='mybutton-edit-{$id}' 
                    data-breadcrumb='Edit' 
                    onclick='my_form.open(this.id)' 
                    data-module='syssetting' 
                    data-url='syssetting/{$id}/edit' 
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
                    $value->key,
                    $value->name,
                    $value->value,
                    $value->description,
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
