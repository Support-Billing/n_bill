<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Services\MyService;
use App\Models\Extension;
use DB;

class ExtensionController extends Controller
{
    
    private $_page_title = 'Extension';
    private $_url_data = 'extension';
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
            return view('content.extension.index', [
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
            return view('content.extension.create', [
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

                $newData = new Extension;
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
            $extension = Extension::findOrFail($id);
            return view('content.extension.edit', [
                'page_title' => $this->_page_title,
                'data' => $extension
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
            $updateData = Extension::findOrFail($id);
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
            $del = Extension::findOrFail($work_location);
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
            $data_cond = $this->_myService->seriliaze_decode($extra_search);

            # Condition
            $cond = array();

            if (!empty($data_cond['keyword'])) {
                $cond[] = ['serverTitle', 'LIKE', '%' . $data_cond['keyword'] . '%'];
            }

            $data_result = Extension::where($cond)->offset($offset)->limit($limit)->get();
            $data_count = Extension::where($cond)->count();


            $rows = array();
            $no = $offset;

            foreach ($data_result as $value) {
                $id = $value->extensionID;
                $linkdelete =  url('extension/'.$id);

                $action = '<div class="text-align-center">';

                $action .= "<a href='javascript:void(0);' 
                    class='btn btn-warning btn-xs margin-right-5' 
                    id='mybutton-edit-{$id}' 
                    data-breadcrumb='Edit' 
                    onclick='my_form.open(this.id)' 
                    data-module='extension' 
                    data-url='extension/{$id}/edit' 
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
                    $value->serverTitle,
                    $value->projectID,
                    $value->extNumber,
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
