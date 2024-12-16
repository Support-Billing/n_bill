<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Services\MyService;
use App\Models\CustomerGroupPrice;
use DB;

class CustomerGroupPriceController extends Controller
{
    
    private $_page_title = 'Customer Group Price';
    private $_url_data = 'customergroup';
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
     * Show the form for editing the specified resource.
     */
    public function customer_group_prices(string $idx)
    {
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            //
            return view('content.customergroup.customer_group_prices', [
                'page_title' => $this->_page_title,
                'idx' => $idx
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store_prices(Request $request, string $idx )
    {
        //
        if($request->ajax())
        {
            $validation = Validator::make($request->all(), [
                "startRange" => ['required'],
                "endRange" => ['required'],
                "tarifPerMenit" => ['required']
            ],[],[
                "startRange" => 'Start range',
                "endRange" => 'End Range',
                "tarifPerMenit" => 'Tarif Per Menit'
            ]);

            if ($validation->fails()) {
                $message = array(false, 'Process Fails', $validation->errors(), '');
            }else{
                
                    
                $idxCustomerGroup = $idx;

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
                    // $newDataPriceId = $newDataPrice->idxCore;
                    
                    // $result = CustomerGroupPrice::where('idxCore', '<>', $newDataPriceId)
                    //     ->where('idxCoreCustGroup',  $idxCustomerGroup)
                    //     ->update(['active' => 0]);
                    
                    // if ($result) {
                    if ($newDataPrice) {
                        $message = array(true, 'Process Successfully', 'The data has been saved.', 'my_data_table.reload(\'#dt_price\')');
                    } else {
                        // Tidak ada baris yang terpengaruh atau terjadi kesalahan
                        $message = array(false, 'Process Fails', 'No data updated or an error occurred.', '');
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
            // echo $idxCustomerGroup;exit;
            $cond[] = ['idxCoreCustGroup', $idxCustomerGroup];
            
            $data_result = CustomerGroupPrice::where($cond)->offset($offset)->limit($limit)->get();
            $data_count = CustomerGroupPrice::where($cond)->count();
            $rows = array();
            $no = $offset;
            
            foreach ($data_result as $value) {
                $id = $value->idxCore;
                $linkdelete =  url('customergroupprice/'.$id);

                $action = '<div class="text-align-center">';
                
                if($this->_access_menu->update_otoritas_modul){
                    
                    $action .= "<a 
                            href='javascript:void(0);'
                            class='btn btn-success btn-xs margin-right-5' 
                            id='mybutton-change-{$idxCustomerGroup}-{$id}'
                            onclick='my_data_table.row_action_change.ajax(this.id)'
                            data-original-title='Change Status' 
                            rel='tooltip'
                            data-url='customergroupprice/{$idxCustomerGroup}/{$id}/change_status'
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
                    // $action = '';
                    $statusData = '<span class="label label-success">Active</span>';
                }

                $no++;
                $rows[] = array(
                    $no,
                    $value->startRange,
                    $value->endRange,
                    $value->tarifPerMenit,
                    $statusData,
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
    
    public function change_status ($idxCustomerGroup = 0, $idx = 0)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            $updateData = CustomerGroupPrice::where('idxCore', $idx)->firstOrFail();

            $updateData->active = !$updateData->active;

            if ($updateData->save()) { 
                $message = array(true, 'Process Successfully', 'Data changed successfully.', 'my_data_table.reload(\'#dt_price\')');
            } else {
                $message = array(false, 'Process Fails', 'Data can\'t be changed.', '');
            }
            
            echo json_encode($message);
        }else{
            return redirect('./#dashboard');
        }
    }

    /* belum jalan */
    public function change_status_tidak_dipakai ($idxCustomerGroup = 0, $idx = 0)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            $result = CustomerGroupPrice::where('idxCustomerGroup', $idxCustomerGroup)  // Filter idgroup=8
                ->update(['active' => 0]);  // Set idgroup=8 menjadi status 0
            
            // Update idx=4 menjadi status 1
            $resultIdx = CustomerGroupPrice::where('idx', $idx)
                ->update(['active' => 1]);
            
            // $updateData = CustomerGroupPrice::where('idx', $idx)->firstOrFail();

            // if ($updateData->active) {
            //     $updateData->active = 0;
            // } else {
            //     $updateData->active = 1;
            // }
            
            if ($result !== false && $resultIdx !== false) {
                $message = array(true, 'Process Successfully', 'Data changed successfully.', 'my_data_table.reload(\'#dt_price\')');
            }else{
                $message = array(false, 'Process Fails', 'Data can\'t changed.', '');
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

            $del = CustomerGroupPrice::findOrFail($idx);
            
            if(!empty($del)){

                $del->deleted_at = now();
                $del->deleted_by = Auth::user()->id;
                if ($del->save()){
                    $message = array(true, 'Process Successfully', 'The data has been Deleted.', 'my_data_table.reload(\'#dt_price\')');
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
