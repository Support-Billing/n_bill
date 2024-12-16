<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\str;

use App\Services\MyService;
use App\Models\User;
use App\Models\WorkLocation;
use DB;

class UserController extends Controller
{

    private $_page_title = 'User';
    private $_url_data = 'user';
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
        return view('content.user.index', [
            'page_title' => $this->_page_title,
            'insert_otoritas_modul' => $this->_access_menu->insert_otoritas_modul
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('content.user.create', [
            'page_title' => 'User'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
    }

    public function profile()
    {
        $worklocations = WorkLocation::all();
        return view('content.user.profile', [
            'page_title' => 'User',
            'worklocations' => $worklocations
        ]);

    }
    
    public function loadprofile(Request $request)
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
            $cond[] = ['username', 'LIKE', '%' . $data_cond['keyword'] . '%'];
        }

        $data_result = User::where($cond)->offset($offset)->limit($limit)->get();
        $data_count = User::where($cond)->count();


        $rows = array();
        $no = $offset;

        foreach ($data_result as $value) {
            $id = $value->id;
            $linkdelete =  url('user/'.$id);

            $action = '<div class="text-align-center">';
            $action .= "<a href='javascript:void(0);' 
                class='btn btn-warning btn-xs margin-right-5' 
                id='mybutton-edit-{$id}' 
                data-breadcrumb='Edit' 
                onclick='my_form.open(this.id)' 
                data-module='user' 
                data-url='user/{$id}/edit' 
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
                $value->id_employee,
                $value->username,
                $value->email,
                $value->id_role,
                $value->status,
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
            $cond[] = ['username', 'LIKE', '%' . $data_cond['keyword'] . '%'];
            // $cond[] = ['employees.name', 'LIKE', '%' . $data_cond['keyword'] . '%'];
        }

        $data_result = User::with('employee','role')->where($cond)->offset($offset)->limit($limit)->get();
        $data_count = User::where($cond)->count();

        $rows = array();
        $no = $offset;

        foreach ($data_result as $value) {

            $id = $value->id;
            $linkdelete =  url('user/'.$id);

            $action = '<div class="text-align-center">';
            $action .= "<a href='javascript:void(0);' 
                class='btn btn-warning btn-xs margin-right-5' 
                id='mybutton-edit-{$id}' 
                data-breadcrumb='Edit' 
                onclick='my_form.open(this.id)' 
                data-module='user' 
                data-url='user/{$id}/edit' 
                data-original-title='Edit' 
                rel='tooltip' 
                data-placement='left'><i class='fa fa-edit'></i></a>";

            $action .= "<a href='javascript:void(0);' 
                class='btn btn-success btn-xs margin-right-5' 
                id='mybutton-overide-U0004' 
                data-confirm='Anda yakin akan mereset password?' 
                onclick='my_data_table.row_action.ajax(this.id)' 
                data-url='/user/{$id}/reset_password'>
                <i class='fa fa-undo'></i></a>";

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
                $value['employee']->name,
                $value->username,
                $value->email,
                $value['role']->name,
                $value->status,
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
