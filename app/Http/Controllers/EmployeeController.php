<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\str;

use App\Services\MyService;
use App\Models\Employee;
use App\Models\WorkLocation;
use DB;

class EmployeeController extends Controller
{
    
    private $_page_title = 'Employee';
    private $_url_data = 'employee';
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
        $worklocations = WorkLocation::all();
        return view('content.employee.index', [
            'page_title' => $this->_page_title,
            'worklocations' => $worklocations,
            'insert_otoritas_modul' => $this->_access_menu->insert_otoritas_modul
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $worklocations = WorkLocation::all();
        return view('content.employee.create', [
            'page_title' => 'Employee',
            'worklocations' => $worklocations
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validation = Validator::make($request->all(), [
            "id_worklocation" => ['required'],
            "name" => ['required'],
            "nik" => ['required'],
            "address" => ['required'],
            "city" => ['required'],
            "phone" => ['required']
        ]);

        if ($validation->fails()) {
            $message = array(false, 'Process Fails', $validation->errors(), '');
        }else{
            
            $id_worklocation = $request->get('id_worklocation');
            $name = $request->get('name');
            $nik = $request->get('nik');
            $address = $request->get('address');
            $phone = $request->get('phone');
            $city = $request->get('city');

            $newData = new Employee;
            $newData->id = str::Uuid(36);
            $newData->name = $name;
            $newData->phone = $phone;
            $newData->nik = $nik;
            $newData->city = $city;
            $newData->address = $address;
            $newData->id_worklocation = $id_worklocation;
            // $newData->created_by = Auth::user()->id;
            if ($newData->save()){
                $message = array(true, 'Process Successfully', 'The data has been saved.', 'my_form.reset(\'#finput\')');
            }else{
                $message = array(false, 'Process Fails', 'The data could not be saved.', '');
            }
        }

        echo json_encode($message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data_result = Employee::with('worklocation')->findOrFail($id);
        return view('content.employee.show', [
            'page_title' => 'Work Location',
            'data_detil' => json_decode($data_result)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $employee = Employee::findOrFail($id);
        $worklocations = WorkLocation::all();
        return view('content.employee.edit', [
            'page_title' => 'Employee',
            'data' => $employee,
            'worklocations' => $worklocations
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {

            $del = Employee::findOrFail($id);
            
            if(!empty($del)){

                $del->deleted_at = now();
                $del->deleted_by = Auth::user()->id;
                if ($del->save()){
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

        $data_result = Employee::with('worklocation')->where($cond)->offset($offset)->limit($limit)->get();
        $data_count = Employee::where($cond)->count();


        $rows = array();
        $no = $offset;

        foreach ($data_result as $value) {
            $id = $value->id;
            $linkdelete =  url('employee/'.$id);

            $action = '<div class="text-align-center">';

            $action .= "<a href='javascript:void(0);'
                class='btn btn-success btn-xs margin-right-5 view-detail'
                id='mybutton-detail-{$id}'
                data-id='{$id}'
                data-source='employee/{$id}'
                data-type='html'
                onclick='my_data_table.row_detail(this.id)'
                data-original-title='View Detail'
                rel='tooltip'
                data-placement='left'><i class='fa fa-angle-down'></i></a>";

            $action .= "<a href='javascript:void(0);' 
                class='btn btn-warning btn-xs margin-right-5' 
                id='mybutton-edit-{$id}' 
                data-breadcrumb='Edit' 
                onclick='my_form.open(this.id)' 
                data-module='employee' 
                data-url='employee/{$id}/edit' 
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
                $value->nik,
                $value->name,
                $value['worklocation']->name,
                $value->phone,
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
    }

}
