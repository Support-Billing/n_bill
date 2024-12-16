<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Services\MyService;
use App\Models\Server;

class ServerController extends Controller
{
    
    private $_page_title = 'Server';
    private $_url_data = 'server';
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
            return view('content.server.index', [
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
            return view('content.server.create', [
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
            $VPN = $request->get('VPN');

            if (!empty($VPN)){
                $validation = Validator::make(
                    $request->all(), 
                    [
                        "serverName" => ['required', 'min:3', 'max:255'],
                        "serverIP" => ['required'],
                        "serverUsername" => ['required'],
                        "serverPassword" => ['required'],
                        "Fingerprint" => ['required'],
                        "serverPort" => ['required'],
                        "serverType" => ['required'],
                        "serverProtocol" => ['required'],
                        "serverVPNName" => ['required'],
                        "serverVPNUsername" => ['required'],
                        "serverVPNPassword" => ['required']
                    ],
                    [],
                    [
                        'serverName' => 'Server Name',
                        'serverIP' => 'IP Address',
                        'serverUsername' => 'Username',
                        'serverPassword' => 'Password',
                        'Fingerprint' => 'Fingerprint',
                        'serverPort' => 'Port',
                        'serverType' => 'Type',
                        'serverProtocol' => 'Protocol',
                        'serverVPNName' => 'VPN Name',
                        'serverVPNUsername' => 'VPN Username',
                        'serverVPNPassword' => 'VPN Password'
                    ]
                );

            }else{
                $validation = Validator::make(
                    $request->all(), 
                    [
                        "serverName" => ['required', 'min:3', 'max:255'],
                        "serverIP" => ['required'],
                        "serverUsername" => ['required'],
                        "serverPassword" => ['required'],
                        "Fingerprint" => ['required'],
                        "serverPort" => ['required'],
                        "serverType" => ['required'],
                        "serverProtocol" => ['required']
                    ],
                    [],
                    [
                        'serverName' => 'Server Name',
                        'serverIP' => 'IP Address',
                        'serverUsername' => 'Username',
                        'serverPassword' => 'Password',
                        'Fingerprint' => 'Fingerprint',
                        'serverPort' => 'Port',
                        'serverType' => 'Type',
                        'serverProtocol' => 'Protocol'
                    ]
                );
            }

            if ($validation->fails()) {
                $message = array(false, 'Process Fails', $validation->errors(), '');
            }else{
                
                $serverName = $request->get('serverName');
                // $serverTitle = $request->get('serverTitle');
                $serverIP = $request->get('serverIP');
                $serverType = $request->get('serverType');
                // $notes = $request->get('notes');
                $statusData = $request->get('statusData');
                $createBy = $request->get('createBy');
                $createDate = $request->get('createDate');
                $modBy = $request->get('modBy');
                $modDate = $request->get('modDate');
                $serverPort = $request->get('serverPort');
                $serverProtocol = $request->get('serverProtocol');
                $serverUsername = $request->get('serverUsername');
                $serverPassword = $request->get('serverPassword');
                $Fingerprint = $request->get('Fingerprint');
                $serverVPNName = $request->get('serverVPNName');
                $serverVPNUsername = $request->get('serverVPNUsername');
                $serverVPNPassword = $request->get('serverVPNPassword');
                

                $newData = new Server;
                // $newData->serverID = $serverID;
                $newData->serverName = $serverName;
                $newData->serverTitle = $serverName;
                // $newData->serverTitle = $serverTitle; serverVPNIPAddress serverSSH serverIP
                $newData->serverIP = $serverIP;
                $newData->serverType = $serverType;
                $newData->notes = $serverName;
                // $newData->notes = $notes;
                $newData->statusData = $statusData;
                $newData->serverPort = $serverPort;
                $newData->serverProtocol = $serverProtocol;
                $newData->serverUsername = $serverUsername;
                $newData->serverPassword = $serverPassword;
                $newData->serverVPNName = $serverVPNName;
                $newData->serverVPNUsername = $serverVPNUsername;
                $newData->serverVPNPassword = $serverVPNPassword;
                $newData->Fingerprint = $Fingerprint;
                // $newData->createBy = $createBy;
                // $newData->createDate = $createDate;
                // $newData->modBy = $modBy;
                // $newData->modDate = $modDate;
                $newData->statusData = 1;
                $newData->created_by = Auth::user()->id;
                // $newData->serverSSH = Auth::user()->id;
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
            $server = server::findOrFail($id);
            return view('content.server.edit', [
                'page_title' => $this->_page_title,
                'data' => $server
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
            $updateData = Server::findOrFail($id);
            $VPN = $request->get('VPN');

            if (!empty($VPN)){
                $validation = Validator::make(
                    $request->all(), 
                    [
                        "serverName" => ['required', 'min:3', 'max:255'],
                        "serverIP" => ['required'],
                        "serverUsername" => ['required'],
                        "serverPassword" => ['required'],
                        "Fingerprint" => ['required'],
                        "serverPort" => ['required'],
                        "serverType" => ['required'],
                        "serverProtocol" => ['required'],
                        "serverVPNName" => ['required'],
                        "serverVPNUsername" => ['required'],
                        "serverVPNPassword" => ['required']
                    ],
                    [],
                    [
                        'serverName' => 'Server Name',
                        'serverIP' => 'IP Address',
                        'serverUsername' => 'Username',
                        'serverPassword' => 'Password',
                        'Fingerprint' => 'Fingerprint',
                        'serverPort' => 'Port',
                        'serverType' => 'Type',
                        'serverProtocol' => 'Protocol',
                        'serverVPNName' => 'VPN Name',
                        'serverVPNUsername' => 'VPN Username',
                        'serverVPNPassword' => 'VPN Password'
                    ]
                );

            }else{
                $validation = Validator::make(
                    $request->all(), 
                    [
                        "serverName" => ['required', 'min:3', 'max:255'],
                        "serverIP" => ['required'],
                        "serverUsername" => ['required'],
                        "serverPassword" => ['required'],
                        "Fingerprint" => ['required'],
                        "serverPort" => ['required'],
                        "serverType" => ['required'],
                        "serverProtocol" => ['required']
                    ],
                    [],
                    [
                        'serverName' => 'Server Name',
                        'serverIP' => 'IP Address',
                        'serverUsername' => 'Username',
                        'serverPassword' => 'Password',
                        'Fingerprint' => 'Fingerprint',
                        'serverPort' => 'Port',
                        'serverType' => 'Type',
                        'serverProtocol' => 'Protocol'
                    ]
                );
            }
            
            if ($validation->fails()) {
                $message = array(false, 'Process Fails', $validation->errors(), '');
            }else{
                $message = array('error', 'Error!', 'An error occurred during storage , please try again.', '');
    
                $serverName = $request->get('serverName');
                // $serverTitle = $request->get('serverTitle');
                $serverIP = $request->get('serverIP');
                $serverType = $request->get('serverType');
                // $notes = $request->get('notes');
                $statusData = $request->get('statusData');
                $createBy = $request->get('createBy');
                $createDate = $request->get('createDate');
                $modBy = $request->get('modBy');
                $modDate = $request->get('modDate');
                $serverPort = $request->get('serverPort');
                $serverProtocol = $request->get('serverProtocol');
                $serverUsername = $request->get('serverUsername');
                $serverPassword = $request->get('serverPassword');
                $Fingerprint = $request->get('Fingerprint');
                $serverVPNName = $request->get('serverVPNName');
                $serverVPNUsername = $request->get('serverVPNUsername');
                $serverVPNPassword = $request->get('serverVPNPassword');
    
                // $updateData->serverID = $serverID;
                $updateData->serverName = $serverName;
                $updateData->serverTitle = $serverName;
                // $updateData->serverTitle = $serverTitle; serverVPNIPAddress serverSSH serverIP
                $updateData->serverIP = $serverIP;
                $updateData->serverType = $serverType;
                $updateData->notes = $serverName;
                // $updateData->notes = $notes;
                $updateData->statusData = $statusData;
                $updateData->serverPort = $serverPort;
                $updateData->serverProtocol = $serverProtocol;
                $updateData->serverUsername = $serverUsername;
                $updateData->serverPassword = $serverPassword;
                $updateData->serverVPNName = $serverVPNName;
                $updateData->serverVPNUsername = $serverVPNUsername;
                $updateData->serverVPNPassword = $serverVPNPassword;
                $updateData->Fingerprint = $Fingerprint;
                // $updateData->createBy = $createBy;
                // $updateData->createDate = $createDate;
                // $updateData->modBy = $modBy;
                // $updateData->modDate = $modDate;
                $updateData->statusData = 1;
                $updateData->created_by = Auth::user()->id;
                // $updateData->serverSSH = Auth::user()->id;
                // $updateData->created_by = Auth::user()->id;
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
    public function change_status ($serverID = 0)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            
            $updateData = Server::where('serverID', $serverID)->firstOrFail();

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
    public function destroy($serverID)
    {
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {

            $del = Server::findOrFail($serverID);
            
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

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            $server = Server::findOrFail($id);
            return view('content.server.show', [
                'page_title' => $this->_page_title,
                'data' => $server
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

            if (!empty($data_cond['keyword'])) {
                $cond[] = ['serverName', 'LIKE', '%' . $data_cond['keyword'] . '%'];
            }

            $data_result = Server::where($cond)->offset($offset)->limit($limit)->get();
            $data_count = Server::where($cond)->count();


            $rows = array();
            $no = $offset;

            foreach ($data_result as $value) {
                $id = $value->serverID;
                $linkdelete =  url('server/'.$id);

                $action = '<div class="text-align-center">';

                    if($this->_access_menu->view_otoritas_modul){
                        $action .= "<a href='javascript:void(0);' 
                            class='btn bg-color-orange btn-xs margin-right-5 text-white' 
                            id='mybutton-show-{$id}' 
                            data-breadcrumb='View' 
                            onclick='my_form.open(this.id)' 
                            data-module='server' 
                            data-url='server/{$id}' 
                            data-original-title='View' 
                            rel='tooltip'
                            data-placement='left'><i class='fa fa-eye'></i></a>";
                    }
                        
                        
                    if($this->_access_menu->update_otoritas_modul){
                        $action .= "<a href='javascript:void(0);' 
                            class='btn btn-warning btn-xs margin-right-5' 
                            id='mybutton-edit-{$id}' 
                            data-breadcrumb='Edit' 
                            onclick='my_form.open(this.id)' 
                            data-module='server' 
                            data-url='server/{$id}/edit' 
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
                            data-url='server/{$id}/change_status'
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
                if ($value->statusData) {
                    # code...
                    $statusData = '<span class="label label-success">Active</span>';
                }

                if($value->serverType == 1){
                    $serverType = 'MERA';
                }elseif($value->serverType == 2){
                    $serverType = 'ELASTIX';
                }else{
                    $serverType = 'VOS';
                }
                $no++;
                $rows[] = array(
                    $no,
                    $value->serverName,
                    $value->serverIP,
                    $serverType,
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
}
