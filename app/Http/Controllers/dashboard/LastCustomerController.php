<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\MyService;
use App\Models\Customer;

class LastCustomerController extends Controller
{ 

    private $_page_title = 'New Customer Last 2 month';
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
            
            return view('dashboard.customer.last', [
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
            $backDate = $myService->getTanggalSebelumnya(2); // --> tidak di gunakan 

            $date = new \DateTime();         // Mendapatkan tanggal sekarang
            $date->modify('-2 months');     // Mengurangi 2 bulan
            $curMonth = $date->format('m');  // Format menjadi "YYYY-MM-DD"
            $curYear=date("Y");
            $curDate="01";
            $backDateN=$curYear."-".$curMonth."-".$curDate;
            // echo $backDateN;exit;
            // $cond[] = ['statusData', 1];
            // $cond[] = ['dateCreated >= DATE_SUB(CURDATE(), INTERVAL 2 MONTH)'];
            
            $isView="";
            // if (checknama user) {
            //     //$isView=" ";
            //     $isView=" AND marketingID =".$_SESSION["userCreator"];
            // }

            // $backDate = $myService->getTanggalSebelumnya(2);
	        // $query="SELECT * FROM customers where custStatus=1 {$isView} and created_at>{$backDate} order by created_at Desc";
            // $data_result = DB::select($query);
            // $data_count = count($data_result);

            $cond[] = ['statusData', 1];
            $data_result = Customer::where($cond)
                ->whereBetween('dateCreated', [
                    DB::raw('DATE_SUB(CURDATE(), INTERVAL 2 MONTH)'),
                    DB::raw('CURDATE()')
                ])
                ->offset($offset)
                ->limit($limit)
                ->get();
            $data_count = Customer::where($cond)
                ->whereBetween('dateCreated', [
                    DB::raw('DATE_SUB(CURDATE(), INTERVAL 2 MONTH)'),
                    DB::raw('CURDATE()')
                ])
                ->count();
                
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

