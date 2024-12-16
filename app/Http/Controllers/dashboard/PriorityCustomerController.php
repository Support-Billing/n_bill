<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\MyService;
use App\Models\Customer;

class PriorityCustomerController extends Controller
{ 

    private $_page_title = 'Priority Customer';
    private $_url_data = 'dashboard';
    private $_myService;
    private $_access_menu;
    

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
            
            return view('dashboard.customer.priority', [
                'page_title' => $this->_page_title
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
            $myService = app(MyService::class);
            $cond[] = ['custStatus', 1];
            $cond[] = ['priority', 1];
            
            $isView="";
            // if (checknama user) {
            //     //$isView=" ";
            //     $isView=" AND marketingID =".$_SESSION["userCreator"];
            // }

            $data_result = Customer::where($cond)->offset($offset)->limit($limit)->get();
            $data_count = Customer::where($cond)->count();
            $rows = array();
            $no = $offset;
            
            foreach ($data_result as $value) {
                $id = $value->idx;
                $linkdelete =  url('customer/'.$id);

                $action = '<div class="text-align-center">';
                
                    if($this->_access_menu->view_otoritas_modul){
                        $action .= "<a href='javascript:void(0);' 
                            class='btn bg-color-orange btn-xs margin-right-5' 
                            id='mybutton-show-{$id}' 
                            data-breadcrumb='View' 
                            onclick='my_form.open(this.id)' 
                            data-module='customer' 
                            data-url='customer/{$id}/show_all' 
                            data-original-title='View' 
                            rel='tooltip'
                            data-placement='left'><i class='fa fa-eye'></i> Manage</a>";
                    }
                    

                $action .= '</div>';

                $no++;
                $rows[] = array(
                    $no,
                    $value->title.'. '.$value->clientName,
                    $value->clientName2,
                    $value->telephone1,
                    $value->contactName,
                    $value->contactName,
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

