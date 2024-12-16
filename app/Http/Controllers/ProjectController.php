<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Services\MyService;

use App\Models\CustomerGroup;
use App\Models\Project;
use App\Models\Prefix;
use App\Models\Customer;
use App\Models\Server;
use App\Models\ProjectPrice;
use App\Models\ProjectPrefixSrv;
use App\Models\ProjectPrefixAccount;
use App\Models\ProjectPrefixIp;
use App\Models\ProjectBillServer;
use DB;


class ProjectController extends Controller
{
    
    private $_page_title = 'Project';
    private $_url_data = 'project';
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
    public function index(Request $request)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            $statusDataGet = $request->get('status');
            $customerGroups = CustomerGroup::all();
            $customers = Customer::all();
            // $customers = Customer::where('statusData', 1)->get();
            $projects = Project::all();
            // $projects = Project::where('statusData', 1)->get();
            // $projectPrefixSrvs = ProjectPrefixSrv::where('active', 1)->get();
            $projectPrefixSrvs = ProjectPrefixSrv::where('active', 1)
                ->select('prefixNumber')  // Memilih kolom prefixNumber
                ->groupBy('prefixNumber') // Mengelompokkan berdasarkan prefixNumber
                ->get();
            $projectIps = ProjectPrefixIp::whereNotNull('IdxCoreProject')->get();

            return view('content.project.index', [
                'page_title' => $this->_page_title,
                'customerGroups' => $customerGroups,
                'projects' => $projects, 
                'customers' => $customers,
                'statusDataGet' => $statusDataGet,
                'projectPrefixSrvs' => $projectPrefixSrvs,
                'projectIps' => $projectIps,
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
            $customers = Customer::all();
            $customerGroups = CustomerGroup::all();
            return view('content.project.create', [
                'page_title' => $this->_page_title,
                'customers' => $customers,
                'customerGroups' => $customerGroups
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
                // "name" => ['required', 'min:3', 'max:255'],
            ]);

            if ($validation->fails()) {
                $message = array(false, 'Process Fails', $validation->errors(), '');
            }else{
                
                $idxCustomer = $request->get('idxCustomer');
                $projectName = $request->get('projectName');
                $contactName = $request->get('contact');
                $email = $request->get('email');
                $telephone = $request->get('telephone');
                $address = $request->get('address');
                $detailProject1 = $request->get('detailProject1');
                $detailProject2 = $request->get('detailProject2');
                $statusData = $request->get('statusData');
                $statusProject = $request->get('statusProject');
                // $createBy = $request->get('createBy');
                // $dateCreated = $request->get('dateCreated');
                $isCLI = $request->get('isCLI');
                $isFWT = $request->get('isFWT');
                $isSIPTRUNK = $request->get('isSIPTRUNK');
                $isSIPREG = $request->get('isSIPREG');
                $startFT = $request->get('ftDateStart');
                $endFT = $request->get('ftDateEnd');
                $startPT = $request->get('ptDateStart');
                $endPT = $request->get('ptDateEnd');
                $isApps = $request->get('isApps');
                $isSLI = $request->get('isSLI');
                $startClient = $request->get('startClient');

                $newData = new Project;
                $newData->idxCustomer = $idxCustomer;
                $newData->projectName = $projectName;
                $newData->contactName = $contactName;
                $newData->email = $email;
                $newData->telephone = $telephone;
                $newData->address = $address;                
                $newData->detailProject1 = $detailProject1;
                $newData->detailProject2 = $detailProject2;
                $newData->statusData = $statusData;
                $newData->statusProject = $statusProject;
                $newData->isCLI = $isCLI;
                $newData->isFWT = $isFWT;
                $newData->isSIPTRUNK = $isSIPTRUNK;
                $newData->isSIPREG = $isSIPREG;
                $newData->startFT = $startFT;
                $newData->endFT = $endFT;
                $newData->startPT = $startPT;
                $newData->endPT = $endPT;
                $newData->isApps = $isApps;
                $newData->isSLI = $isSLI;
                $newData->startClient = $startClient;
                $newData->statusData = 1;

                $newData->created_by = Auth::user()->id;
                if ($newData->save()){
                    $projectID = $newData->projectID;
                    $newDataPrice = new ProjectPrice;

                    $newMobile = $request->get('newMobile');
                    $newMobileBC = $request->get('newMobileBC');
                    $newPSTN = $request->get('newPSTN');
                    $newPSTNBC = $request->get('newPSTNBC');
                    $newPremium = $request->get('newPremium');
                    $newPremiumBC = $request->get('newPremiumBC');
                    $newMinComm = $request->get('newMinComm');
                    $isPriority = $request->get('isPriority');

                    $newDataPrice->projectID = $projectID;
                    $newDataPrice->priceMobile = $newMobile;
                    $newDataPrice->pricePSTN = $newPSTN;
                    $newDataPrice->pricePremium = $newPremium;
                    $newDataPrice->bcMobile = $newMobileBC;
                    $newDataPrice->bcPSTN = $newPSTNBC;
                    $newDataPrice->bcPremium = $newPremiumBC;
                    $newDataPrice->price = $newMinComm;
                    $newDataPrice->priority = $isPriority;
                    
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
    public function show(string $id)
    {
        //
        // echo 'aa';exit;
        $data_result = Project::with('customer')->findOrFail($id);
        return view('content.project.show', [
            'page_title' => 'Project Detil',
            'data_detil' => json_decode($data_result)
            // 'jumlah_project' => $data_result['project']->count()
        ]);
    }

    public function show_all(string $idxCore)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            
            
            $customer = Customer::whereHas('projects', function ($query) use ($idxCore) {
                $query->where('idxCore', $idxCore);
            })->first();
            
            $data_detil_project = Project::where('idxCore', $idxCore)->firstOrFail();
            // print_r($data_detil_project);exit;
            return view('content.project.show_all', [
                'page_title' => $this->_page_title,
                'data_detil' => $customer,
                'data_detil_project' => $data_detil_project
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
        // echo 'aaa';exit;
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            $project = Project::findOrFail($id);
            $customers = Customer::all();
            return view('content.project.edit', [
                'page_title' => $this->_page_title,
                'customers' => $customers,
                'data' => $project
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
            $updateData = Project::findOrFail($id);
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
     * Update the specified resource in storage.
     */
    public function change_status ($projectID = 0)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            
            $updateData = Project::where('projectID', $projectID)->firstOrFail();

            if ($updateData->statusData) {
                $updateData->statusData = 0;
            } else {
                $updateData->statusData = 1;
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
    public function destroy($project)
    {
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {

            $del = Project::findOrFail($project);
            
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

            // if (!empty($data_cond['keyword'])) {
            //     $cond[] = ['projectAlias', 'LIKE', '%' . $data_cond['keyword'] . '%'];
            // }
            $idxCoreCustomerGroups = array();
            $idxCoreCustomers = array();
            $idxCoreProjects = array();
            $projectPrefixSrv = array();
            $projectPrefixIp = array();
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
                if ($item['name'] === 'projectPrefixSrv[]') {
                    $projectPrefixSrv[] = $item['value'];
                }
                if ($item['name'] === 'projectIp[]') {
                    $projectPrefixIp[] = $item['value'];
                }
            }

            if (isset($data_cond['isCLI'])) {
                $cond[] = ['isCLI',  $data_cond['isCLI'] ];
            }

            if (isset($data_cond['statusData'])) {
                $cond[] = ['statusData',  $data_cond['statusData'] ];
            }

            if (isset($data_cond['statusProject'])) {
                $cond[] = ['statusProject',  $data_cond['statusProject'] ];
            }
            
            $data_result = Project::where($cond)
            ->when(!empty($idxCoreCustomerGroups), function ($query) use ($idxCoreCustomerGroups) {
                return $query->whereIn('idxCoreCustGroup', $idxCoreCustomerGroups);
            })
            ->when(!empty($idxCoreCustomers), function ($query) use ($idxCoreCustomers) {
                return $query->whereIn('idxCoreCust', $idxCoreCustomers);
            })
            ->when(!empty($idxCoreProjects), function ($query) use ($idxCoreProjects) {
                return $query->whereIn('idxCore', $idxCoreProjects);
            })
            ->when(!empty($projectPrefixSrv), function ($query) use ($projectPrefixSrv) {
                return $query->whereHas('ProjectPrefixSrvs', function ($query) use ($projectPrefixSrv) {
                    $query->whereIn('prefixNumber', $projectPrefixSrv);
                });
            })
            ->when(!empty($projectPrefixIp), function ($query) use ($projectPrefixIp) {
                return $query->whereHas('ProjectPrefixIps', function ($query) use ($projectPrefixIp) {
                    $query->whereIn('idxCore', $projectPrefixIp);
                });
            })->offset($offset)->limit($limit)->get();
            $data_count = Project::where($cond)->count();


            $rows = array();
            $no = $offset;

            foreach ($data_result as $value) {
                // print_r($value);exit;
                $id = $value->idxCore; 
                $linkdelete =  url('project/'.$id);

                $action = '<div class="text-align-center">';
                
                
                    if($this->_access_menu->view_otoritas_modul){
                        $action .= "<a href='javascript:void(0);' 
                            class='btn bg-color-orange btn-xs margin-right-5 text-white' 
                            id='mybutton-show-{$id}' 
                            data-breadcrumb='View' 
                            onclick='my_form.open(this.id)' 
                            data-module='project' 
                            data-url='project/{$id}/show_all' 
                            data-original-title='View' 
                            rel='tooltip'
                            data-placement='left'><i class='fa fa-eye'></i></a>";
                            
                        $action .= "<a href='javascript:void(0);'
                            class='btn btn-success btn-xs margin-right-5 view-detail'
                            id='mybutton-detail-{$id}'
                            data-id='{$id}'
                            data-source='project/{$id}'
                            data-type='html'
                            onclick='my_data_table.row_detail(this.id)'
                            data-original-title='View Detail'
                            rel='tooltip'
                            data-placement='left'><i class='fa fa-angle-down'></i></a>";
                    }

                    if($this->_access_menu->update_otoritas_modul){
                        $action .= "<a 
                            href='javascript:void(0);'
                            class='btn btn-success btn-xs margin-right-5' 
                            id='mybutton-change-{$id}'
                            onclick='my_data_table.row_action_change.ajax(this.id)'
                            data-original-title='Change Status' 
                            rel='tooltip'
                            data-url='project/{$id}/change_status'
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

                // $statusData = '<span class="label label-warning">Inactive</span>';
                // if ($value->statusData) {
                //     # code...
                //     $statusData = '<span class="label label-success">Active</span>';
                // }
                switch ($value->statusProject) {
                    case 0:
                        $statusData = '<span class="label label-success">Wait</span>';
                        break;
                    case 1:
                        $statusData = '<span class="label label-success">Free Trial</span>';
                        break;
                    case 2:
                        $statusData = '<span class="label label-success">Pre Paid Trial</span>';
                        break;
                    case 3:
                        $statusData = '<span class="label label-success">Subscribe</span>';
                        break;
                    case 4:
                        $statusData = '<span class="label label-warning">Closed</span>';
                        break;
                    
                    default:
                        # code...
                        break;
                }
                
                $dateMod = $value->dateMod;
                $modBy = $value->modBy;
                if ($value->dateMod == '0000-00-00') {
                    $dateMod = '';
                    $modBy = '';
                }

                
                $data_all_prefix_project = ProjectPrefixSrv::where('idxCoreProject', $id)->get();
                $temp_dataPrefix = '';
                foreach ($data_all_prefix_project as $prefix_project){
                    $temp_dataPrefix .= $prefix_project->prefixNumber.' ';
                }
                
                $no++;
                $rows[] = array(
                    $no,
                    // $value->projectName,
                    $value->projectAlias,
                    $temp_dataPrefix,
                    $modBy,
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
    
    public function show_all_table(Request $request)
    {

        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            // $CID = $request->get('CID');
            
            $idxCoreProject = $request->get('projectID');

            $data_detil_project = Project::where('idxCore', $idxCoreProject)->firstOrFail();
            // print_r ($data_detil_project->idxProjectFPortal);exit;

            $data_all_prefix_project = ProjectPrefixSrv::where('idxCoreProject', $idxCoreProject)->get();
            // $data_all_account_project = ProjectPrefixAccount::where('idxCore', $projectID)->get();
            $data_all_ip_project = ProjectPrefixIp::where('idxCoreProject', $idxCoreProject)->get();
            // $data_all_billserver = ProjectBillServer::where('projectID', $projectID)->get();
            // $data_all_billserver = ProjectBillServer::where('idxCore', $projectID)->get();

            // $data_all_prefix_project = array();
            $data_all_account_project = array();
            // $data_all_ip_project = array();
            $data_all_billserver = array();

            // print_r($data_all_ip_project);exit;

            // $project = project::findOrFail($id);
            // $ProjectPrefixSrv = ProjectPrefixSrv::where('projectID', $projectID)->get();
            return view('content.project.table_project', [
                'page_title' => $this->_page_title,
                'data_detil_project' => $data_detil_project,
                'prefix_projects' => $data_all_prefix_project,
                'account_projects' => $data_all_account_project,
                'ip_projects' => $data_all_ip_project,
                'billservers' => $data_all_billserver
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }

    public function project_price($projectID = 0)
    {
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            return view('content.project.child.project_price', [
                'page_title' => 'Add Project Price',
                'projectID' => $projectID
            ]);
        }else{
            return redirect('./#dashboard');
        }
 
    }
    
    public function project_prefix($projectID = 0)
    {

        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            return view('content.project.child.project_prefix', [
                'page_title' => 'Add Project Prefix',
                'projectID' => $projectID
            ]);
        }else{
            return redirect('./#dashboard');
        }
 
    }
    
    // public function project_accounts($projectID = 0)
        // {
        //     if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        //     {
        //         return view('content.project.child.project_accounts', [
        //             'page_title' => 'Add Project Account',
        //             'projectID' => $projectID
        //         ]);
        //     }else{
        //         return redirect('./#dashboard');
        //     }
    // }

    public function project_prefixip($projectID = 0)
    {
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            return view('content.project.child.project_prefixip', [
                'page_title' => 'Add Project Source IP(customer/origin) to End Point',
                'projectID' => $projectID
            ]);
        }else{
            return redirect('./#dashboard');
        }
 
    }

    public function project_prefixsvr($projectID = 0)
    {
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            $servers = Server::all();
            return view('content.project.child.project_prefixsvr', [
                'page_title' => 'Add Project Server IP Destination (End Point)',
                'projectID' => $projectID,
                'servers' => $servers
            ]);
        }else{
            return redirect('./#dashboard');
        }
 
    }


    public function store_price(Request $request, $projectID)
    {
        if($request->ajax())
        {
            $validation = Validator::make($request->all(), [
                // "name" => ['required', 'min:3', 'max:255'],
            ]);

            if ($validation->fails()) {
                $message = array(false, 'Process Fails', $validation->errors(), '');
            }else{
                
                $newDataPrice = new ProjectPrice;

                $newMobile = $request->get('newMobile');
                $newMobileBC = $request->get('newMobileBC');
                $newPSTN = $request->get('newPSTN');
                $newPSTNBC = $request->get('newPSTNBC');
                $newPremium = $request->get('newPremium');
                $newPremiumBC = $request->get('newPremiumBC');
                $newMinComm = $request->get('newMinComm');
                $isPriority = $request->get('isPriority');

                $newDataPrice->idxCoreProject = $projectID;
                $newDataPrice->priceMobile = $newMobile;
                $newDataPrice->pricePSTN = $newPSTN;
                $newDataPrice->pricePremium = $newPremium;
                $newDataPrice->bcMobile = $newMobileBC;
                $newDataPrice->bcPSTN = $newPSTNBC;
                $newDataPrice->bcPremium = $newPremiumBC;
                $newDataPrice->price = $newMinComm;
                $newDataPrice->priority = $isPriority;
                
                if ($newDataPrice->save()){
                    $message = array(true, 'Process Successfully', 'The data has been saved.', 'pagefunction(\'reset_price\')');
                }else{
                    $message = array(false, 'Process Fails', 'The data could not be saved.', '');
                }
            }

            echo json_encode($message);
        }else{
            return redirect('./#dashboard');
        }

    }

    public function store_prefix(Request $request, $projectID)
    {
        if($request->ajax())
        {
            $validation = Validator::make($request->all(), [
                "prefixNumber" => ['required', 'min:3', 'max:255'],
            ]);

            if ($validation->fails()) {
                $message = array(false, 'Process Fails', $validation->errors(), '');
            }else{
                
                $newData = new ProjectPrefixSrv;

                $prefixNumber = $request->get('prefixNumber');

                $newData->idxCoreProject = $projectID;
                $newData->prefixNumber = $prefixNumber;
                
                if ($newData->save()){
                    // $message = array(true, 'Process Successfully', 'The data has been saved.', 'pagefunction(\'reset_data\');$("#remoteModal").modal("hide"); $("#remoteModal_lg").modal("hide");');

                    $message = array(true, 'Process Successfully', 'The data has been saved.', 'pagefunction(\'reset_data_close_modal\')');
                }else{
                    $message = array(false, 'Process Fails', 'The data could not be saved.', '');
                }
            }

            echo json_encode($message);
        }else{
            return redirect('./#dashboard');
        }

    }

    // public function store_accounts(Request $request, $projectID)
        // {
        //     if($request->ajax())
        //     {
        //         $validation = Validator::make($request->all(), [
        //             "accountNumber" => ['required', 'min:3', 'max:255'],
        //         ]);

        //         if ($validation->fails()) {
        //             $message = array(false, 'Process Fails', $validation->errors(), '');
        //         }else{
                    
        //             $newData = new ProjectPrefixAccount;

        //             $accountNumber = $request->get('accountNumber');

        //             $newData->projectID = $projectID;
        //             $newData->accountNumber = $accountNumber;
                    
        //             if ($newData->save()){
        //                 $message = array(true, 'Process Successfully', 'The data has been saved.', 'pagefunction(\'reset_data_close_modal\')');
        //             }else{
        //                 $message = array(false, 'Process Fails', 'The data could not be saved.', '');
        //             }
        //         }

        //         echo json_encode($message);
        //     }else{
        //         return redirect('./#dashboard');
        //     }
    // }

    public function store_prefixip(Request $request, $projectID)
    {
        if($request->ajax())
        {
            $validation = Validator::make($request->all(), [
                "ipNumber" => ['required', 'min:3', 'max:255'],
            ]);

            if ($validation->fails()) {
                $message = array(false, 'Process Fails', $validation->errors(), '');
            }else{
                
                $newData = new ProjectPrefixIp;

                $ipNumber = $request->get('ipNumber');

                $newData->idxCoreProject = $projectID;
                $newData->startIP = $ipNumber;
                
                if ($newData->save()){
                    $message = array(true, 'Process Successfully', 'The data has been saved.', 'pagefunction(\'reset_data_close_modal\')');
                }else{
                    $message = array(false, 'Process Fails', 'The data could not be saved.', '');
                }
            }

            echo json_encode($message);
        }else{
            return redirect('./#dashboard');
        }

    }
    public function store_prefixsvr(Request $request, $projectID)
    {
        if($request->ajax())
        {
            $validation = Validator::make($request->all(), [
                "serverID" => ['required'],
            ]);

            if ($validation->fails()) {
                $message = array(false, 'Process Fails', $validation->errors(), '');
            }else{
                
                $newData = new ProjectBillServer;

                $serverID = $request->get('serverID');
                $serverName = $request->get('serverName');

                $newData->projectID = $projectID;
                $newData->serverID = $serverID;
                $newData->serverName = $serverName;
                
                if ($newData->save()){
                    $message = array(true, 'Process Successfully', 'The data has been saved.', 'pagefunction(\'reset_data_close_modal\')');
                }else{
                    $message = array(false, 'Process Fails', 'The data could not be saved.', '');
                }
            }

            echo json_encode($message);
        }else{
            return redirect('./#dashboard');
        }

    }
    
   public function trash ($projectID = 0, $priceID = 0)
   {
       //
       if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
       {
           $message = array('error', 'Error!', 'An error occurred during storage , please try again.', '');

           $del = ProjectPrice::where('projectID', $projectID)
            ->where('priceID', $priceID)
            ->firstOrFail();


            $del->deleted_at = now();
            $del->deleted_by = Auth::user()->id;
            if ($del->save()){
                $message = array(true, 'Process Successfully', 'The data has been Deleted.', 'my_data_table.reload(\'#dt_price\')');
            }else{
                $message = array(false, 'Process Fails', 'Data can\'t Deleted.', '');
            }
           
           echo json_encode($message);
       }else{
           return redirect('./#dashboard');
       }
   }
}
