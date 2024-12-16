<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Services\MyService;

use App\Models\CustomerGroup;
use App\Models\Customer;
use App\Models\User;
use App\Models\Project;

class CustomerController extends Controller
{

    private $_page_title = 'Customer';
    private $_url_data = 'customer';
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
            // $customerGroups = CustomerGroup::where('active', 1)->get();
            $customerGroups = CustomerGroup::all();
            // $customers = Customer::where('statusData', 1)->get();
            $customers = Customer::all();
            // $projects = Project::where('statusData', 1)->get();
            $projects = Project::all();
            return view('content.customer.index', [
                'page_title' => $this->_page_title,
                'customerGroups' => $customerGroups,
                'projects' => $projects,
                'customers' => $customers,
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
        # Condition
        $cond = array();
        // $cond[] = ['id_role', '2946254f-5ac5-442d-bd4e-f71779390ca7'];
        $UserResults = User::with('employee','role')->where($cond)->get();
        $customerGroups = CustomerGroup::all();
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            return view('content.customer.create', [
                'page_title' => $this->_page_title,
                'customerGroups' => $customerGroups,
                'UserResults' => $UserResults
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
                "titleCompany" => ['required'],
            ]);

            if ($validation->fails()) {
                $message = array(false, 'Process Fails', $validation->errors(), '');
            }else{
                
                $clientName = $request->get('clientName');
                $clientName2 = $request->get('clientName2');
                $contact = $request->get('contact');
                $contact2 = $request->get('contact2');
                $telephone1 = $request->get('telephone1');
                $address1 = $request->get('address1');
                $address2 = $request->get('address2');
                $email1 = $request->get('email1');
                $email2 = $request->get('email2');
                $proposed = $request->get('proposed');
                $approached = $request->get('approached');
                $marketing1 = $request->get('marketing1');
                $taxID = $request->get('taxID');
                $otherDetails = $request->get('otherDetails');
                $leads = $request->get('leads');
                $taxAddress = $request->get('taxAddress');
                $titleCompany = $request->get('titleCompany');
                $marketing2 = $request->get('marketing2');
                $approachedDate = $request->get('approachedDate');
                $proposedDate = $request->get('proposedDate');
                $fax = $request->get('fax');
                $invoicePrior = $request->get('invoicePrior');
                $customNote = $request->get('customNote');
                $isCustom = $request->get('isCustom');
                $isCompare = $request->get('isCompare');
                $isTier = $request->get('isTier');
                $invoicePrior = $request->get('invoicePrior');

                $marketingName=Auth::user()->id;

                // change tanggal
                $approachedDate = $this->_myService->switch_tanggal($approachedDate, 5);
                $proposedDate = $this->_myService->switch_tanggal($proposedDate, 5);

                $newData = new Customer;

                $newData->idxCore =  Str::uuid()->toString();
                $newData->clientName = $clientName; 
                $newData->clientName2 = $clientName2;
                $newData->contactName = $contact; 
                $newData->contactName2 = $contact2; 
                $newData->telephone1 = $telephone1;
                $newData->address1 = $address1; 
                $newData->address2 = $address2; 
                $newData->email1 = $email1; 
                $newData->email2 = $email2;
                $custStatus=1;
                if($approached==1 && $proposed==1){
                    $custStatus=3;
                }else if($approached==1 ){
                    $custStatus=1;
                }else if($proposed==1){
                    $custStatus=2;
                }
                $newData->custStatus = $custStatus; 
                // $newData->marketingID = $marketing1;
                $newData->idxCoreMarketing1 = $marketing1;
                $newData->taxID = $taxID; 
                $newData->otherDetails = $otherDetails; 
                $newData->leads = $leads; 
                $newData->taxAddress = $taxAddress; 
                $newData->title = $titleCompany;
                // $newData->marketingID2 = $marketing2;
                $newData->idxCoreMarketing2 = $marketing2;
                $newData->submitDate = $approachedDate; 
                $newData->prospectDate = $proposedDate;
                $newData->fax = $fax; 
                $newData->invoicePrior = $invoicePrior; 
                if($invoicePrior=='priority'){
                    $newData->priority = 1;
                }
                $newData->customText = $customNote; 
                $newData->isCustom = $isCustom; 
                $newData->isInv = $isCompare; 
                $newData->isTier = $isTier;

                $newData->created_by = Auth::user()->id;
                // print_r($newData);exit;
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data_result = Customer::with('projects')->findOrFail($id);
        return view('content.customer.show', [
            'page_title' => 'Customer Detil',
            'data_detil' => json_decode($data_result),
            'jumlah_project' => $data_result['projects']->count()
        ]);
    }
    
    public function show_all(string $idcore)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            $customer = Customer::with('projects')->findOrFail($idcore);
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
    public function edit(string $idcore)
    {
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            # Condition
            $cond = array();
            $customer = Customer::findOrFail($idcore);
            $UserResults = User::with('employee','role')->where($cond)->get();
            $customerGroups = CustomerGroup::all();
            return view('content.customer.edit', [
                'page_title' => $this->_page_title,
                'data' => $customer,
                'customerGroups' => $customerGroups,
                'UserResults' => $UserResults
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
            $updateData = Customer::findOrFail($id);
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
    public function destroy($customer)
    {
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            $del = Customer::findOrFail($customer);
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
            // $between = false;
            // $myService = app(MyService::class);

            // if (!empty($data_cond['keyword'])) {
            //     $cond[] = ['clientName', 'LIKE', '%' . $data_cond['keyword'] . '%'];
            // }

			// if (!empty($data_cond['ftDateStart'])) {
			// 	$date_start = $data_cond['ftDateStart'];
            //     $format_date_start = $myService->switch_tanggal($date_start, 3);
            //     if (!empty($data_cond['ftDateEnd'])) {
			// 		$date_end = $data_cond['ftDateEnd'];
            //         $format_date_end = $myService->switch_tanggal($date_end, 3);
            //         $between = true;
			// 	}else{
            //         $cond[] = ['created_at', 'LIKE', '%' . $format_date_start . '%'];
			// 	}
			// }

			// if ($between) {
            //     $data_result = Customer::whereBetween('created_at', [$format_date_start, $format_date_end])->where($cond)->offset($offset)->limit($limit)->get();
            //     $data_count = Customer::whereBetween('created_at', [$format_date_start, $format_date_end])->where($cond)->count();
            // }else{
            //     $data_result = Customer::where($cond)->offset($offset)->limit($limit)->get();
            //     $data_count = Customer::where($cond)->count();
            // }

            $idxCoreCustomerGroups = array();
            $idxCoreCustomers = array();
            $idxCoreProjects = array();
            foreach ($extra_search as $item) {
                if ($item['name'] === 'idxCoreCustomerGroup[]') {
                    $idxCoreCustomerGroups[] = $item['value'];
                }
                if ($item['name'] === 'idxCoreCustomer[]') {
                    $idxCoreCustomers[] = $item['value'];
                }
                if ($item['name'] === 'idxCoreProject[]') {
                    $idxCoreProjects[] = $item['value'];
                }
            }

            if (isset($data_cond['isTier'])) {
                $cond[] = ['isTier',  $data_cond['isTier'] ];
            }
            // print_r($idxCoreCustomerGroups);exit;

            $data_result = Customer::where($cond)
            ->when(!empty($idxCoreCustomerGroups), function ($query) use ($idxCoreCustomerGroups) {
                return $query->whereIn('idxCoreCustGroup', $idxCoreCustomerGroups);
            })
            ->when(!empty($idxCoreCustomers), function ($query) use ($idxCoreCustomers) {
                return $query->whereIn('idxCore', $idxCoreCustomers);
            })
            ->when(!empty($idxCoreProjects), function ($query) use ($idxCoreProjects) {
                return $query->whereHas('projects', function ($query) use ($idxCoreProjects) {
                    $query->whereIn('idxCore', $idxCoreProjects);
                });
            })->offset($offset)->limit($limit)->get();
            $data_count = Customer::where($cond)->count();

            $rows = array();
            $no = $offset;
            
            foreach ($data_result as $value) {
                $id = $value->idxCore;
                // echo $id;exit; 
                $linkdelete =  url('customer/'.$id);

                $action = '<div class="text-align-center">';
                
                    if($this->_access_menu->view_otoritas_modul){
                        $action .= "<a href='javascript:void(0);' 
                            class='btn bg-color-orange btn-xs margin-right-5 text-white' 
                            id='mybutton-show-{$id}' 
                            data-breadcrumb='View' 
                            onclick='my_form.open(this.id)' 
                            data-module='customer' 
                            data-url='customer/{$id}/show_all' 
                            data-original-title='View' 
                            rel='tooltip'
                            data-placement='left'><i class='fa fa-eye'></i></a>";

                        $action .= "<a href='javascript:void(0);'
                            class='btn btn-success btn-xs margin-right-5 view-detail'
                            id='mybutton-detail-{$id}'
                            data-id='{$id}'
                            data-source='customer/{$id}'
                            data-type='html'
                            onclick='my_data_table.row_detail(this.id)'
                            data-original-title='View Detail'
                            rel='tooltip'
                            data-placement='left'><i class='fa fa-angle-down'></i></a>";
                    }
                    if($this->_access_menu->update_otoritas_modul){
                        $action .= "<a href='javascript:void(0);' 
                            class='btn btn-warning btn-xs margin-right-5' 
                            id='mybutton-edit-{$id}' 
                            data-breadcrumb='Edit' 
                            onclick='my_form.open(this.id)' 
                            data-module='customer' 
                            data-url='customer/{$id}/edit' 
                            data-original-title='Edit' 
                            rel='tooltip'
                            data-placement='left'><i class='fa fa-edit'></i></a>";

                        // $action .= "<a href='javascript:void(0);' 
                        //     class='btn btn-success btn-xs margin-right-5' 
                        //     id='mybutton-overide-U0004' 
                        //     data-confirm='Anda yakin akan merubah status?' 
                        //     onclick='my_data_table.row_action.ajax(this.id)' 
                        //     data-url='/bank/{$id}/change_status'
                        //     data-original-title='Change Status' 
                        //     rel='tooltip'
                        //     data-placement='left' >
                        //     <i class='fa fa-exchange'></i></a>";
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

                $titleWithClientName = $value->clientName;
                if ($value->title !== null) {
                    $titleWithClientName = $value->title . '. ' . $value->clientName;
                    // Gunakan $titleWithClientName sesuai kebutuhan Anda, misalnya ditampilkan atau disimpan ke variabel lain
                }
                
                $no++;
                $rows[] = array(
                    $no,
                    $titleWithClientName,
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
    

    public function load_project(Request $request)
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
            $idcore = $data_cond['idxCustomer'];
            $cond[] = ['idxCoreCust', $data_cond['idxCustomer'] ];

            $query  = Project::where($cond)->offset($offset)->limit($limit);

            // // Mendapatkan query SQL
            // $sql = $query->toSql();
            
            // // Tampilkan query
            // dd($sql);
            
            // Eksekusi query
            $data_result = $query->get();
            $data_count = Project::where($cond)->count();


            $rows = array();
            $no = $offset;

            foreach ($data_result as $value) {
                $id = $value->idxCore;

                $action = '<div class="text-align-center">';

                    if($this->_access_menu->delete_otoritas_modul){
                        // $action .= "<a 
                        //         href='javascript:void(0);'
                        //         class='btn btn-danger btn-xs margin-right-5' 
                        //         id='mybutton-change-{$idcore}-{$id}'
                        //         onclick='my_data_table.row_action_change.ajax(this.id)'
                        //         data-original-title='Delete' 
                        //         rel='tooltip'
                        //         data-url='customer/{$idcore}/{$id}/destroy_project'
                        //         ><i class='fa fa-trash-o'></i></a>";
                        
                        $action .= "<a href='javascript:void(0);' 
                            class='btn bg-color-orange btn-xs margin-right-5 text-white' 
                            id='mybutton-show-{$id}' 
                            data-breadcrumb='View' 
                            onclick='my_form.open(this.id)' 
                            data-module='customer' 
                            data-url='project/{$id}/show_all' 
                            data-original-title='View' 
                            rel='tooltip'
                            data-placement='left'><i class='fa fa-eye'></i></a>";

                    }

                $action .= '</div>';

                $statusData = '<span class="label label-warning">Inactive</span>';
                if ($value->statusData) {
                    # code...
                    $statusData = '<span class="label label-success">Active</span>';
                }
                
                $dateMod = $value->dateMod;
                $modBy = $value->modBy;
                if ($value->dateMod == '0000-00-00') {
                    $dateMod = '';
                    $modBy = '';
                }
                $no++;
                $rows[] = array(
                    $value->projectID.' - '.$value->projectName,
                    $value->detailProject1,
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


    /**
     * Show the form for editing the specified resource.
     */
    public function customer_project(string $idxCoreCust)
    {
        // echo $idxCoreCust;exit;
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            // $projects = Project::whereNull('idxCoreCust')->orWhere('idxCoreCust', '')->Where('idxCoreCust', '!=', $idxCoreCust)
            //         // ->get();
            //        ->toSql();

            // dd($projects); // atau var_dump($projects) jika tidak menggunakan Laravel's dd()
            // $projects = Project::whereNull('idxCoreCust')->orWhere('idxCoreCust', '')->get();
            // $projects = Project::whereNull('idxCoreCust')
            //        ->orWhere('idxCoreCust', '')
            //        ->toSql();

            // dd($projects); // atau var_dump($projects) jika tidak menggunakan Laravel's dd()

            // $projects = Project::where(function($query) {
            //     $query->whereNull('idxCoreCust')
            //           ->orWhere('idxCoreCust', '');
            // })->where('idxCoreCust', '!=', $idxCoreCust)->get();
            
            // $projects = Project::whereNull('idxCoreCust')->orWhere('idxCoreCust', '')->Where('idxCoreCust', '!=', $idxCoreCust);
            $projects = Project::Where('idxCoreCust', '!=', $idxCoreCust)->get();

            return view('content.customer.customer_project', [
                'page_title' => $this->_page_title . ' Project',
                'idxCoreCust' => $idxCoreCust, //
                'projects' => $projects
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store_project(Request $request, string $idxCustomer )
    {
        //
        if($request->ajax())
        {
            $validation = Validator::make($request->all(), [
                "projectID" => ['required']
            ],[],[
                "projectID" => 'Project ID',
            ]);
            if ($validation->fails()) {
                $message = array(false, 'Process Fails', $validation->errors(), '');
            }else{
                $message = array('error', 'Error!', 'An error occurred during storage , please try again.', '');
    
                $projectID = $request->get('projectID');
                $updateData = Project::findOrFail($projectID);
    
                $updateData->idxCustomer = $idxCustomer;
                $updateData->updated_by = Auth::user()->id;
                if ($updateData->save()){
                    $message = array(true, 'Process Successful', 'Data updated successfully.', 'my_data_table.reload(\'#dt_project\')');
                }else{
                    $message = array(false, 'Process Fails', 'The data could not be updated.', '');
                }
            }
    
            echo json_encode($message);
        }else{
            return redirect('./#dashboard');
        }
    }
    
    // delete list project di page
    public function destroy_project ($idxCustomer = 0, $projectID = 0)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            
            $del = Project::findOrFail($projectID);
            
            if(!empty($del)){
                $del->idxCustomer = Null;
                $del->updated_by = Auth::user()->id;
                
                if ($del->save()){
                    $message = array(true, 'Process Successfully', 'The data has been Deleted.', 'my_data_table.reload(\'#dt_project\')');
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

    
}
