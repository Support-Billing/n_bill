<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\MyService;

use App\Models\customergroupprice;
use App\Models\ProjectPrice;
use DB;

class ProjectPriceController extends Controller
{
    
    private $_page_title = 'Project Price';
    private $_url_data = 'project';
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
            $projectID = $data_cond['projectID'];
            $cond[] = ['idxCoreProject', $data_cond['projectID'] ];

            $data_result = ProjectPrice::where($cond)->offset($offset)->limit($limit)->get();
            $data_count = ProjectPrice::where($cond)->count();
            
            $rows = array();
            $no = $offset;

            foreach ($data_result as $value) {
                $priceID = $value->priceID;

                $action = '<div class="text-align-center">';

                    if($this->_access_menu->delete_otoritas_modul){
                        $action .= "<a 
                            href='javascript:void(0);'
                            class='btn btn-danger btn-xs margin-right-5' 
                            id='mybutton-change-{$priceID}'
                            onclick='my_data_table.row_action_change.ajax(this.id)'
                            data-original-title='Delete' 
                            rel='tooltip'
                            data-url='project/{$projectID}/{$priceID}/trash'
                            ><i class='fa fa-trash-o'></i></a>";
                    }

                $action .= '</div>';

                $statusData = '<span class="label label-warning">Inactive</span>';
                if ($value->status) {
                    $statusData = '<span class="label label-success">Active</span>';
                }
                
                $dateMod = $value->dateMod;
                $modBy = $value->modBy;
                if ($value->dateMod == '0000-00-00') {
                    $dateMod = '';
                    $modBy = '';
                }
                
                $date = '-'; // Default jika tanggal kosong
                if (!empty($value->created_at)) {
                    $date = new \DateTime($value->created_at); // Menghapus extra $
                }

                $no++;
                $rows[] = array(
                    $value->priceMobile,
                    $value->pricePSTN,
                    $value->pricePremium,
                    $value->price,
                    $value->priority,
                    $statusData,
                    is_string($date) ? $date : date_format($date, 'Y-m-d'),            
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
