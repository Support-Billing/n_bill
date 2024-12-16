<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Services\MyService;
use App\Models\PrefixSupplier;
use App\Models\PrefixGroup;
use App\Models\Customer;
use DB;

class PrefixSupplierController extends Controller
{
    
    private $_page_title = 'Prefix Supplier';
    private $_url_data = 'prefixsupplier';
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
            return view('content.prefixsupplier.index', [
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
            return view('content.prefixsupplier.create', [
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PrefixSupplier $prefixSupplier)
    {
        //
    }

    public function show_all(int $idcore)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            $customer = Customer::with('project')->findOrFail($idcore);
            return view('content.customer.show_all', [
                'page_title' => $this->_page_title,
                'data_detil' => $customer
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PrefixSupplier $prefixSupplier)
    {
        //
    }

    public function update_cdr(string $idcore)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            return view('content.customer.cdr', [
                'page_title' => $this->_page_title
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PrefixSupplier $prefixSupplier)
    {
        //
    }

    public function edit_cdr(string $idcore)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            return view('content.customer.cdr', [
                'page_title' => $this->_page_title
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PrefixSupplier $prefixSupplier)
    {
        //
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

            $data_result = PrefixGroup::where($cond)->offset($offset)->limit($limit)->get();
            $data_count = PrefixGroup::where($cond)->count();


            $rows = array();
            $no = $offset;

            foreach ($data_result as $value) {
                $id = $value->idx;
                $linkdelete =  url('prefixsupplier/'.$id);

                $action = '<div class="text-align-center">';

                $action .= "<a href='javascript:void(0);' 
                    class='btn bg-color-orange btn-xs margin-right-5' 
                    id='mybutton-show-{$id}' 
                    data-breadcrumb='View' 
                    onclick='my_form.open(this.id)' 
                    data-module='prefixsupplier' 
                    data-url='prefixsupplier/{$id}/show_all' 
                    data-original-title='View' 
                    rel='tooltip'
                    data-placement='left'><i class='fa fa-eye'></i></a>";
                    
                $action .= "<a href='javascript:void(0);' 
                    class='btn btn-warning btn-xs margin-right-5' 
                    id='mybutton-edit-{$id}' 
                    data-breadcrumb='Edit' 
                    onclick='my_form.open(this.id)' 
                    data-module='prefixsupplier' 
                    data-url='prefixsupplier/{$id}/edit' 
                    data-original-title='Edit' 
                    rel='tooltip'
                    data-placement='left'><i class='fa fa-edit'></i></a>";

                $action .= "<a href='javascript:void(0);' 
                    class='btn btn-success btn-xs margin-right-5' 
                    id='mybutton-overide-U0004' 
                    data-confirm='Anda yakin akan merubah status?' 
                    onclick='my_data_table.row_action.ajax(this.id)' 
                    data-url='/prefixsupplier/{$id}/change_status'
                    data-original-title='Change Status' 
                    rel='tooltip'
                    data-placement='left' >
                    <i class='fa fa-exchange'></i></a>";

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
                    $value->nama,
                    $value->detikAwal,
                    $value->active,
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
