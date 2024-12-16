<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Services\MyService;
use App\Models\CustomerGroupMember ;
use App\Models\Customer;
use App\Models\Project;
use DB;

class CustomerGroupMemberController extends Controller
{
    
    private $_page_title = 'Customer Group Member';
    private $_url_data = 'customergroup';
    private $_id_role = '';
    private $_access_menu;
    private $_myService;
    private $_DataGroup;
    private $_DataProject;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_myService = app(MyService::class);
        $this->middleware('auth');
        // $this->get_group();
        $this->get_project();
        $this->middleware(function ($request, $next) {
            $this->_access_menu = $this->_myService->parser_access_menu($this->_url_data);
            return $next($request);
        });
    }

    public function get_group() {
        $CustomerGroupMembers = CustomerGroupMember::get();
        $getDataGroup = array();
        foreach ($CustomerGroupMembers as $key => $val) {
            $getDataGroup[$val->idxCustomerGroup][$val->idxCustomer][$val->idxProject] = $val->active;
        }
        // print_r($CustomerGroupMembers);exit;
        $this->_DataGroup = $getDataGroup;
        
        return 'succsess';
    }
    
    public function get_project() {
        $Projects = Project::get();
        $getDataProject = array();
        foreach ($Projects as $key => $val) {
            $getDataProject[$val->idxDesktop][$val->idxCore] = $val->idxCore;
        }
        $this->_DataProject = $getDataProject;
        
        return 'succsess';
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store_members(Request $request, string $idx )
    {
        echo "Controllernya ada di sini dengan fungsinya menjadi untuk update data Project";
        
        //
        if($request->ajax())
        {
            $validation = Validator::make($request->all(), [
                "idxCustomer" => ['required']
            ],[],[
                "idxCustomer" => 'Customer',
            ]);

            if ($validation->fails()) {
                $message = array(false, 'Process Fails', $validation->errors(), '');
            }else{
                $idxCustomerGroup = $idx;
                $idxCustomer = $request->get('idxCustomer');
                $newDataMember = new CustomerGroupMember;

                $newDataMember->idxCustomerGroup = $idxCustomerGroup;
                $newDataMember->idxCustomer = $idxCustomer;
                $newDataMember->created_by = Auth::user()->id;

                if ($newDataMember->save()){
                    $message = array(true, 'Process Successfully', 'The data has been saved.', 'my_data_table.reload(\'#dt_member\')');
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
    public function customer_group_project(int $idxCustomerGroup, int $idxCustomer)
    {
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            // $projects = DB::table('projects')
            // ->select('projectID')
            // ->where('idxCustomer', $idxCustomer)
            // ->whereDoesntHave('customerGroupMembers', function ($query) use ($idxCustomerGroup, $idxCustomer) {
            //     $query->where('idxCustomerGroup', $idxCustomerGroup)
            //           ->where('idxCustomer', $idxCustomer);
            // })
            // ->get();

            
            // $projectsQuery = DB::table('projects')
            //     ->select('projectID', 'projectName')
            //     ->where('idxCustomer', '=', $idxCustomer)
            //     ->whereNotIn('projectID', function ($query) use ($idxCustomerGroup, $idxCustomer) {
            //         $query->select('idxProject')
            //             ->from('customer_group_members')
            //             ->where('idxCustomerGroup', '=', $idxCustomerGroup)
            //             ->where('idxCustomer', '=', $idxCustomer);
            //     });
                
            // // dd($projectsQuery->toSql());
            // $projects = $projectsQuery->get();



            $query = "
            SELECT projectID , projectName
            FROM projects p
            WHERE idxDesktop = $idxCustomer
              AND NOT EXISTS (
                SELECT $idxCustomer
                FROM customer_group_members cgm 
                WHERE  cgm.idxCustomerGroupDesktop = $idxCustomerGroup
                  AND cgm.idxProjectDesktop = $idxCustomer
              );
            ";

            $projects = DB::select($query);

            return view('content.customergroup.customer_group_project', [
                'page_title' => $this->_page_title . ' Project',
                'idxCustomer' => $idxCustomer, //
                'idxCustomerGroup' => $idxCustomerGroup, //
                'query' => $query,
                'projects' => $projects
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }
    
    public function customer_group_list_project(string $idxCustomerGroup,string $idxCustomer)
    {
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            # Condition
            // $cond = array();
            // $cond[] = ['idxCustomerGroupDesktop', $idxCustomerGroup];
            // $cond[] = ['idxCustomerClientID', $idxCustomer];
            // $cond[] = ['customer_group_members.active', 1];
            // $projects = CustomerGroupMember::with('project')->where($cond)->get();
            // $cond = array();
            // $CustomerGroupPrice = CustomerGroup::with('prices')->where($cond)->findOrFail($idx);
            
            // $query = "
            // SELECT 
            // idxDesktop, projectAlias
            // FROM customer_group_members
            // LEFT JOIN projects ON projects.idxDesktop = customer_group_members.idxProjectDesktop
            // WHERE idxCustomerGroupDesktop = '2015'
            // AND idxCustomerClientID = '653'
            // AND customer_group_members.active = 1;
            // ";

            // SELECT 
            // idxDesktop, projectAlias
            // FROM customer_group_members
            // LEFT JOIN projects ON projects.idxDesktop = customer_group_members.idxProjectDesktop
            // WHERE idxCustomerGroupDesktop = {$idxCustomerGroup}
            // AND idxCustomerClientID = {$idxCustomer}
            // AND customer_group_members.active = 1;
                            // --GROUP BY idxDesktop, projectAlias
                        
            // $data_result = DB::select($query);
            // $query = "SELECT idxProjectDesktop FROM customer_group_members ";

            
            $query = "
            SELECT 
                idxCoreCustGroup, idxFDesktop, projectAlias
            FROM projects 
                WHERE idxCoreCustGroup = '$idxCustomerGroup'
                AND idxCoreCust = '$idxCustomer';
            ";
            // echo $query;exit;

            $projects = DB::select($query);

            return view('content.customergroup.customer_group_list_project', [
                'page_title' => $this->_page_title . ' Project',
                'projects' => $projects
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function customer_group_members(string $idxCustomerGroup)
    {
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            //select * from `customers` where `idx` not in (select `idxCustomer` from `customer_group_members`) and `customers`.`deleted_at` is null;
            // $customers = Customer::notInGroup()->get();
            // $customersWithoutProjects = Customer::doesntHave('projects')->get();
            
            $arrayIdxCoreCust = Project::where('idxCoreCustGroup', $idxCustomerGroup)
                ->distinct('idxCoreCust')
                ->pluck('idxCoreCust')
                ->toArray();

            $customers = Customer::whereNotIn('idxCore', $arrayIdxCoreCust)->get();

            return view('content.customergroup.customer_group_members', [
                'page_title' => $this->_page_title ,
                'idx' => $idxCustomerGroup,
                'customers' => $customers
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
            $idxCustomerGroup = $data_cond['idxcustomergroup'];
            $arrayIdxCoreCust = Project::where('idxCoreCustGroup', $idxCustomerGroup)
                ->distinct('idxCoreCust')
                ->pluck('idxCoreCust')
                ->toArray();
                
            $data_result = Customer::with('projects')->whereIn('idxCore', $arrayIdxCoreCust)->offset($offset)->limit($limit)->get();
            $data_count = Customer::with('projects')->whereIn('idxCore', $arrayIdxCoreCust)->count();
            
            $rows = array();
            $no = $offset;
            
            foreach ($data_result as $value) {
                $idxCoreCustomer = $value->idxCore;
                $linkdelete =  url('customergroupmember/'.$idxCoreCustomer);
                
                $action = '<div class="text-align-center">';
                
                // if (!empty($value->jumlahProject)) {
                    $action .= <<<HTML
                        <a href="customergroupmember/{$idxCustomerGroup}/{$idxCoreCustomer}/customer_group_list_project" 
                           id="mybutton-add-project-{$idxCoreCustomer}"
                           class="btn bg-color-orange btn-xs margin-right-5 text-white" 
                           data-toggle="modal" 
                           data-target="#remoteModal_lg">
                           <i class="fa fa-eye"></i>
                        </a>
                    HTML;
                    
                    $action .= "<a 
                        href='javascript:void(0);'
                        class='btn btn-danger btn-xs' 
                        id='mybutton-delete-{$idxCoreCustomer}'
                        onclick='my_data_table.row_action.ajax(this.id)'
                        data-original-title='Delete' 
                        rel='tooltip' 
                        data-url='{$linkdelete}'
                        ><i class='fa fa-trash-o'></i></a>";
                // }

                // if (!empty($jumlahCustomerProject)) {
                //     $action .= <<<HTML
                //         <a href='customergroupmember/{$idxCustomerGroup}/{$idxCoreCustomer}/customer_group_project' 
                //         id='mybutton-add-project-{{$idxCoreCustomer}}'
                //         class='btn btn-primary btn-xs margin-right-5' 
                //         data-toggle='modal' 
                //         data-target='#remoteModal'><i class='fa fa-plus'></i> Project</a>
                //     HTML;
                // }

                $action .= '</div>';
                // print_r($value['projects']);exit;
                $no++;
                // $projects = $value['projects'];
                // if (is_array($projects) && count($projects) > 0) {
                //     foreach ($projects as $project) {
                //         print_r($project);
                //         exit;
                //         // $idxCoreProjects[] = $project->idxCore;
                //     }
                // }
                
                $rows[] = array(
                    $no,
                    // $value->clientName . '<br /> Jumlah Project :' . $jumlahCustomerProject,
                    $value->clientName,
                    $value['projects']->count(),
                    $this->_myService->button_action($action)
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
    
    public function get_selection_project(Request $request)
    {
        
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            $idxCoreCustomers = $request->post('customers');

            $CustomerProjects = Project::when(!empty($idxCoreCustomers), function ($query) use ($idxCoreCustomers) {
                return $query->whereIn('idxCoreCust', $idxCoreCustomers);
            })->orderBy('idxCoreCust', 'asc')->get();

            return view('content.customergroup.projectSelect', [
                'CustomerProjects' => $CustomerProjects
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_project(Request $request, string $idxCustomerGroup, string $idxCustomer)
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
                $newDataMember = new CustomerGroupMember;

                $newDataMember->idxCustomerGroup = $idxCustomerGroup;
                $newDataMember->idxCustomer = $idxCustomer;
                $newDataMember->idxProject = $projectID;
                $newDataMember->created_by = Auth::user()->id;

                if ($newDataMember->save()){
                    $message = array(true, 'Process Successfully', 'The data has been saved.', 'my_data_table.reload(\'#dt_member\')');
                }else{
                    $message = array(false, 'Process Fails', 'The data could not be saved.', '');
                }
            }
    
            echo json_encode($message);
        }else{
            return redirect('./#dashboard');
        }
    }
    
    public function destroy($idx)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {

            $del = CustomerGroupMember::findOrFail($idx);
            
            if(!empty($del)){

                $del->deleted_at = now();
                $del->deleted_by = Auth::user()->id;
                if ($del->save()){
                    $message = array(true, 'Process Successfully', 'The data has been Deleted.', 'my_data_table.reload(\'#dt_member\')');
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
