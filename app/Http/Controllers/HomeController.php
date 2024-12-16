<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

use App\Services\MyService;
use App\Exports\ProjectExport;

use App\Models\Project;

class HomeController extends Controller
{
    private $_temp_menu = array();
    private $_html_menu = '';
    private $_html;
    private $_temp;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // phpinfo();exit;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data_nav = $this->nav();
        return view('home', [
            'data_nav' => $data_nav
        ]);
    }
    
    public function dashboard()
    {
        
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            // $backDate = date('Y-m-d', strtotime("-2 months"));

            $curMonth=date("mm")-2;
            $curYear=date("Y");
            $curDate="01";
            $backDate=$curYear."-".$curMonth."-".$curDate;
            $query_customers = "
            SELECT
                COUNT(IF(custStatus=1 AND dateCreated >= DATE_SUB(CURDATE(), INTERVAL 2 MONTH), idxCore, NULL)) AS Total_New, -- Total New 2 months
                COUNT(IF(custStatus IN (0, 1), idxCore, NULL)) AS Total_Active, -- Total Active
                COUNT(IF(custStatus=3, idxCore, NULL)) AS Total_Close, -- Total Close
                COUNT(IF(custStatus=1 AND priority=1, idxCore, NULL)) AS Total_Prior, -- Total Prior
                COUNT(IF( custStatus=3 and statusData, idxCore, NULL)) AS Total_Non_Active -- Total Non Active
            FROM customers;
        ";
        
            // $query_customers = "
            //     SELECT
            //         COUNT(IF( custStatus=1 and created_at > '$backDate', idxCore, NULL)) AS Total_New, -- Total New 2 mounth
            //         COUNT(IF( custStatus=1, idxCore, NULL)) AS Total_Active, -- Total Active
            //         COUNT(IF( custStatus=1 and priority=1, idxCore, NULL)) AS Total_Prior, -- Total Prior
            //         COUNT(IF( custStatus=3, idxCore, NULL)) AS Total_Non_Active -- Total Non Active
            //     FROM customers;
            // ";

            // $query_projects = "
            //     SELECT  
            //         COUNT(IF( statusData=1, idxCore, NULL)) AS Total_Active_Project, -- Total Active Project --> Total Project
            //         COUNT(IF( statusData=1 and statusProject=0, idxCore, NULL)) AS Total_Active_Project_Waiting, -- Total Active Project Waiting
            //         COUNT(IF( statusData=1 and statusProject>=1, idxCore, NULL)) AS Active_Project_Status_Apply, -- Active Project Status Apply --> Active Project
            //         COUNT(IF( statusData=1 and statusProject=1, idxCore, NULL)) AS Total_Trial, -- Total Trial --> Free Trial
            //         COUNT(IF( statusData=1 and statusProject=2, idxCore, NULL)) AS Total_Trial_Pay, -- Total Trial Pay -->Trial Subscribe
            //         COUNT(IF( statusData=1 and statusProject=3, idxCore, NULL)) AS Total_On_Pay -- Total On Pay -->Subscribe
            //     FROM projects;
            // ";

            $query_projects = "
                SELECT  
                    COUNT(IF(statusProject IN (0, 1, 2, 3) and statusData=1 , idxCore, NULL)) AS Total_Project, -- Total Project
                    COUNT(IF(statusProject=3 and statusData=1, idxCore, NULL)) AS Total_Active_Project, -- Total Active Project
                    COUNT(IF( statusProject=0 and statusData=1 , idxCore, NULL)) AS Total_Active_Project_Waiting, -- Total Active Project Waiting
                    COUNT(IF( statusProject=1 and statusData=1, idxCore, NULL)) AS Total_Trial, -- Total Trial --> Free Trial
                    COUNT(IF( statusProject=2 and statusData=1, idxCore, NULL)) AS Total_Trial_Pay, -- Total Trial Pay -->Trial Subscribe
                    COUNT(IF( statusProject=3 and statusData=1, idxCore, NULL)) AS Total_On_Pay -- Total On Pay -->Subscribe
                FROM projects;
            ";
            
			// $sqlLastPrefix = "
            //     SELECT 
            //         a.prefixNumber, 
            //         b.projectName 
            //     FROM 
            //         `project_prefix_srv` a 
            //     LEFT JOIN 
            //         `projects` b 
            //     ON 
            //         a.projectIDFPortal = b.idxProjectFPortal 
            //     ORDER BY 
            //         a.prefixNumber DESC 
            //     LIMIT 
            //         0, 1;
            // ";
            $sqlLastPrefix = "
                -- SELECT COUNT(idxCore) as total_count
                -- FROM project_prefix_srv
                -- WHERE created >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                -- AND created_at < CURDATE();


                SELECT prefixNumber as total_count FROM `dev_billsystem`.`project_prefix_srv` ORDER BY `created` DESC limit 1
            ";
            
			$sqlServer = "
                SELECT count(*) as total from servers
            ";

            $data_customers  = DB::select($query_customers);
            $data_projects  = DB::select($query_projects);
            // print_r($data_projects);exit;
            $data_last_prefix = DB::select($sqlLastPrefix);
            $data_servers  = DB::select($sqlServer);
            $countprefixNumber = 0;
            if (!empty($data_last_prefix)) {
                $countprefixNumber = collect($data_last_prefix)->pluck('total_count')->first();
            }
            
            return view('content.dashboard', [
                'data_customers' => $data_customers[0],
                'data_projects' => $data_projects[0],
                'data_last_prefix' => $countprefixNumber,
                'data_servers' => $data_servers[0],
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }
    
    public function download($dataStatus)
    {
        // $data = project::where('statusData', 1)
        //                ->select('projectID', 'projectName')
        //                ->get();
        // return Excel::download(function($excel) use ($data) {
        //     $excel->sheet('Sheet 1', function($sheet) use ($data) {
        //         $sheet->fromArray($data->toArray());
        //     });
        // }, 'example.xls');
        $namaFile = ($dataStatus == 'active') ? 'project_active' : 'all_project' ;
        return Excel::download(new ProjectExport($dataStatus), $namaFile.'.xlsx');
    }
    
    private function _list($key = array()) {
        $id_user = Auth::user()->id_role;
        $query = "SELECT `b`.* FROM `otoritas_moduls` `a` JOIN `moduls` `b` ON `a`.`id_menu` = `b`.`id` WHERE `a`.`id_role` = '{$id_user}' AND `a`.`view_otoritas_modul` = '1' ORDER BY `b`.`list_number`";
        $data = DB::select($query);

        $temp = array();
        foreach ($data as $value) {
            $parent_id = $value->m_id;
            $parent = !empty($parent_id) ? $parent_id : 0;
            $menu_url = '#';

            if (!empty($value->url)) {
                $menu_url = $value->url;
            }

            $temp[$parent][] = (object) array(
                'menu_id' => $value->id,
                'menu_name' => $value->name,
                'menu_icon' => $value->icon,
                'menu_url' => $menu_url
            );
        }
        $this->_temp = $temp;
    }

    private function _parsing($parent_id = 0, $submenu = true) {
        if (isset($this->_temp[$parent_id])) {
            foreach ($this->_temp[$parent_id] as $menu) {
                $menu_id = $menu->menu_id;
                $nama = '<span class="menu-item-parent">' . $menu->menu_name . '</span>';
                $url = $menu->menu_url;
                $menu_icon = trim($menu->menu_icon);

                if (!empty($menu_icon)) {
                    $nama = '<i class="fa fa-lg fa-fw ' . $menu_icon . '"></i>  ' . $nama;
                }

                if ($submenu) {
                    if (isset($this->_temp[$menu_id])) {
                        $this->_html .= '<li><a title="' . $menu->menu_name . '" href="' . $url . '"> ' . $nama . ' </a>';
                        $this->_html .= '  <ul>';
                        $this->_parsing($menu_id);
                        $this->_html .= '  </ul>';
                        $this->_html .= '</li>';
                    } else {
                        $this->_html .= '<li><a title="' . $menu->menu_name . '" href="' . $url . '"> ' . $nama . ' </a></li>';
                    }
                } else {
                    $this->_html .= '<li><a title="' . $menu->menu_name . '" href="' . $url . '"> ' . $nama . ' </a></li>';
                }
            }
        }
    }

    public function nav($key = array()) {
        $this->_list($key);
        $this->_html = '<ul>';
        $this->_parsing(0);
        $this->_html .= '</ul>';
        return $this->_html;
    }
}
