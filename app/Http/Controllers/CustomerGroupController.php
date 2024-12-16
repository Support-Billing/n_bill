<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Services\MyService;
use App\Models\User;
use App\Models\CustomerGroup;
use App\Models\Customer;
use App\Models\Project;
use App\Models\CustomerGroupPrice;
// use App\Models\CustomerGroupMember;
use DB;

class CustomerGroupController extends Controller
{
    
    private $_page_title = 'Customer Group';
    private $_url_data = 'customergroup';
    private $_id_role = '';
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
            // $customerGroups = CustomerGroup::get();
            $customerGroups = CustomerGroup::all();
            // $customers = Customer::where('statusData', 1)->get();
            // $customers = Customer::get();
            $customers = Customer::all();
            // $projects = Project::where('statusData', 1)->get();
            // $projects = Project::get();
            $projects = Project::all();
            return view('content.customergroup.index', [
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
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            return view('content.customergroup.create', [
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
        if($request->ajax())
        {
            $validation = Validator::make($request->all(), [
                "name" => ['required'],
                "startRange" => ['required'],
                "endRange" => ['required'],
                "tarifPerMenit" => ['required']
            ],[],[
                "name" => 'Name',
                "startRange" => 'Start range',
                "endRange" => 'End Range',
                "tarifPerMenit" => 'Tarif Per Menit'
            ]);

            if ($validation->fails()) {
                $message = array(false, 'Process Fails', $validation->errors(), '');
            }else{
                
                $name = $request->get('name');
                $telkomPrice = $request->get('telkomPrice');
                $newData = new CustomerGroup;

                $newData->idxCore =  Str::uuid()->toString();
                $newData->name = $name; 
                $newData->created_by = Auth::user()->id;
                
                if ($newData->save()){
                    
                    $idxCustomerGroup = $newData->idxCore;

                    $startRange = $request->get('startRange');
                    $endRange = $request->get('endRange');
                    $tarifPerMenit = $request->get('tarifPerMenit');
                    $newDataPrice = new CustomerGroupPrice;

                    $newDataPrice->idxCore =  Str::uuid()->toString();
                    $newDataPrice->idxCoreCustGroup = $idxCustomerGroup;
                    $newDataPrice->startRange = $startRange;
                    $newDataPrice->endRange = $endRange;
                    $newDataPrice->tarifPerMenit = $tarifPerMenit;
                    $newDataPrice->created_by = Auth::user()->id;

                    if ($newDataPrice->save()){
                        $message = array(true, 'Process Successfully', 'The data has been saved.', 'my_form.reset(\'#finput\')');
                    }else{
                        $message = array(false, 'Process Fails', 'The data could not be saved.', '');
                    }

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
    public function show(string $idx)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            $CustomerGroup = CustomerGroup::with('prices')->findOrFail($idx);
            return view('content.customergroup.show', [
                'page_title' => $this->_page_title . 'aaa',
                'data_detil' => $CustomerGroup
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }

    public function show_all(string $idx)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            $CustomerGroupPrice = CustomerGroup::with('prices', 'projects')->findOrFail($idx);
            
            return view('content.customergroup.show_all', [
                'page_title' => $this->_page_title,
                'insert_otoritas_modul' => $this->_access_menu->insert_otoritas_modul,
                'data_detil' => $CustomerGroupPrice
            ]);
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
            # Condition
            $cond = array();
            $customer = CustomerGroup::findOrFail($id);
            $UserResults = User::with('employee','role')->where($cond)->get();
            return view('content.customergroup.edit', [
                'page_title' => $this->_page_title,
                'data' => $customer,
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
            $updateData = CustomerGroup::findOrFail($id);
            $validation = Validator::make($request->all(), [
                "name" => ['required']
            ],[],[
                "name" => 'Name'
            ]);
            if ($validation->fails()) {
                $message = array(false, 'Process Fails', $validation->errors(), '');
            }else{
                $message = array('error', 'Error!', 'An error occurred during storage , please try again.', '');
    
                $name = $request->get('name');
    
                $updateData->name = $name;
                $updateData->updated_by = Auth::user()->id;
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
     * Update the specified resource in storage.
     */
    public function change_status ($idx = 0)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            
            $updateData = CustomerGroup::where('idxCore', $idx)->firstOrFail();

            if ($updateData->active) {
                $updateData->active = 0;
            } else {
                $updateData->active = 1;
            }
            
            if ($updateData->save()){
                $message = array(true, 'Process Successfully', 'Data changed successfully.', 'my_data_table.reload(\'#dt_basic\')');
            }else{
                $message = array(false, 'Process Fails', 'Data can\'t changed.', '');
            }
            
            echo json_encode($message);
        }else{
            return redirect('./#dashboard');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($idx)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {

            $del = CustomerGroup::findOrFail($idx);
            
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

            if (isset($data_cond['statusData'])) {
                $cond[] = ['active',  $data_cond['statusData'] ];
            }

            $data_result = CustomerGroup::with('prices', 'projects')
            ->when(!empty($idxCoreCustomerGroups), function ($query) use ($idxCoreCustomerGroups) {
                return $query->whereIn('idxCore', $idxCoreCustomerGroups);
            })
            ->when(!empty($idxCoreCustomers), function ($query) use ($idxCoreCustomers) {
                return $query->whereHas('customers', function ($query) use ($idxCoreCustomers) {
                    $query->whereIn('idxCore', $idxCoreCustomers);
                });
            })
            ->when(!empty($idxCoreProjects), function ($query) use ($idxCoreProjects) {
                return $query->whereHas('projects', function ($query) use ($idxCoreProjects) {
                    $query->whereIn('idxCore', $idxCoreProjects);
                });
            })
            ->where($cond)->offset($offset)->limit($limit)->get();


            $data_count = CustomerGroup::with('prices', 'projects')
            ->when(!empty($idxCoreCustomerGroups), function ($query) use ($idxCoreCustomerGroups) {
                return $query->whereIn('idxCore', $idxCoreCustomerGroups);
            })
            ->when(!empty($idxCoreCustomers), function ($query) use ($idxCoreCustomers) {
                return $query->whereHas('customers', function ($query) use ($idxCoreCustomers) {
                    $query->whereIn('idxCore', $idxCoreCustomers);
                });
            })
            ->when(!empty($idxCoreProjects), function ($query) use ($idxCoreProjects) {
                return $query->whereHas('projects', function ($query) use ($idxCoreProjects) {
                    $query->whereIn('idxCore', $idxCoreProjects);
                });
            })
            ->where($cond)->count();

            // $data_count = CustomerGroup::with('prices', 'projects')->where($cond)->count();
            $rows = array();
            $no = $offset;
            
            foreach ($data_result as $value) {
                $id = $value->idxCore;
                $linkdelete =  url('customergroup/'.$id);

                $action = '<div class="text-align-center">';
                
                    if($this->_access_menu->view_otoritas_modul){
                        $action .= "<a href='javascript:void(0);' 
                            class='btn bg-color-orange btn-xs margin-right-5 text-white' 
                            id='mybutton-show-{$id}' 
                            data-breadcrumb='View' 
                            onclick='my_form.open(this.id)' 
                            data-module='customergroup' 
                            data-url='customergroup/{$id}/show_all' 
                            data-original-title='View' 
                            rel='tooltip'
                            data-placement='left'><i class='fa fa-eye'></i></a>";

                        $action .= "<a href='javascript:void(0);'
                            class='btn btn-success btn-xs margin-right-5 view-detail'
                            id='mybutton-detail-{$id}'
                            data-id='{$id}'
                            data-source='customergroup/{$id}'
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
                            data-module='customergroup' 
                            data-url='customergroup/{$id}/edit' 
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
                                data-url='customergroup/{$id}/change_status'
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
                if ($value->active) {
                    $statusData = '<span class="label label-success">Active</span>';
                }
                
                // Menghitung jumlah harga (prices)
                $jumlahHarga = $value->prices->count();

                // Menghitung jumlah project (projects)
                $jumlahProject = $value->projects->count();

                
                // Mendapatkan idCustomer yang unik berdasarkan idprices
                $idxCoreCust = Project::where('idxCoreCustGroup', $id)
                                ->distinct('idxCoreCust')
                                ->pluck('idxCoreCust')
                                ->toArray();

                // Menghitung jumlah customer (idCustomers)
                $jumlahCustomer = count($idxCoreCust);

                $no++;
                $rows[] = array(
                    $no,
                    $value->name,
                    $value['prices']->count(),
                    $jumlahCustomer,
                    $value['projects']->count(),
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
    
    


    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store_project(Request $request, string $idxCustomer )
    // {
    //     //
    //     if($request->ajax())
    //     {
    //         $validation = Validator::make($request->all(), [
    //             "projectID" => ['required']
    //         ],[],[
    //             "projectID" => 'Project ID',
    //         ]);
            
    //         if ($validation->fails()) {
    //             $message = array(false, 'Process Fails', $validation->errors(), '');
    //         }else{
    //             $message = array('error', 'Error!', 'An error occurred during storage , please try again.', '');
    
    //             $projectID = $request->get('projectID');
    //             $updateData = Project::findOrFail($projectID);
    
    //             $updateData->idxCustomer = $idxCustomer;
    //             $updateData->updated_by = Auth::user()->id;
    //             if ($updateData->save()){
    //                 $message = array(true, 'Process Successful', 'Data updated successfully.', 'my_data_table.reload(\'#dt_member\')');
    //             }else{
    //                 $message = array(false, 'Process Fails', 'The data could not be updated.', '');
    //             }
    //         }
    
    //         echo json_encode($message);
    //     }else{
    //         return redirect('./#dashboard');
    //     }
    // }
}
