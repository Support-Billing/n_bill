<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\str;

use App\Services\MyService;
use App\Models\Modul;
use DB;

class MenuController extends Controller
{
    private $_page_title = 'Menu';
    private $_url_data = 'menu';
    private $_temp_menu = array();
    private $_temp_ordering = array();
    private $_html_menu;
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
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            return view('content.menu.index', [
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
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            //
            return view('content.menu.create', [
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

            // make(array $data, array $rules, array $messages = [], array $attributes = [])
            $validation = Validator::make(
                $request->all(), 
                [
                    "menu_name" => [ 'required', 'min:3', 'max:100'],
                    "menu_url" => ['required', 'min:1', 'max:100']
                ],
                [],
                [
                    'menu_name' => 'Menu Name',
                    'menu_url' => 'URL'
                ]
            );

            if ($validation->fails()) {
                $message = array(false, 'Process Fails', $validation->errors(), '');
            }else{
                
                $menu_name = $request->get('menu_name');
                $menu_url = $request->get('menu_url');
                $menu_icon = $request->get('menu_icon');
                $m_id = $request->get('m_id');

                $newData = new Modul;
                $newData->id = str::Uuid(36);
                $newData->name = $menu_name;
                $newData->url = $menu_url;
                $newData->icon = $menu_icon;
                if (!empty($m_id)) {
                    $newData->m_id = $m_id;
                }
                $newData->list_number = 0;
                if ($newData->save()){
                    $message = array(true, 'Process Successfully', 'The data has been saved.', 'load_and_reset_form()');
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
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            //
            $list_data_menu = $this->_admin();
            return view('content.menu.nestable', [
                'page_title' => $this->_page_title,
                'list_data_menu' => $list_data_menu
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit_menu(string $id)
    {
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            //
            $modul = Modul::where('id', $id)->firstOrFail();
            return view('content.menu.edit', [
                'page_title' => $this->_page_title,
                'data' => $modul
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function add_child(string $m_id)
    {
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            //
            return view('content.menu.create_child', [
                'page_title' => $this->_page_title,
                'm_id' => $m_id,
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

            $updateData = Modul::where('id', $id)->firstOrFail();
            $validation = Validator::make(
                $request->all(), 
                [
                    "menu_name" => [ 'required', 'min:3', 'max:100'],
                    "menu_url" => ['required', 'min:1', 'max:100']
                ],
                [],
                [
                    'menu_name' => 'Menu Name',
                    'menu_url' => 'URL'
                ]
            );

            if ($validation->fails()) {
                $message = array(false, 'Process Fails', $validation->errors(), '');
            }else{

                $menu_name = $request->get('menu_name');
                $menu_url = $request->get('menu_url');
                $menu_icon = $request->get('menu_icon');

                $updateData->name = $menu_name;
                $updateData->url = $menu_url;
                $updateData->icon = $menu_icon;
                
                if ($updateData->save()){
                    $message = array(true, 'Process Successfully', 'Data updated successfully.', 'pagefunction()');
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
    public function destroy(string $id)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            $del = Modul::findOrFail($id);
            
            if(!empty($del)){
                
                $cond[] = ['m_id', '=', $id];

                $count_result = Modul::where($cond)->count();
                if ($count_result){
                    $message = array(false, 'Process Fails', 'Failed to delete, The data have child.', '');
                }else{
                    if ($del->delete()){
                        $message = array(true, 'Process Successfully', 'The data has been Deleted.', 'pagefunction()');
                    }else{
                        $message = array(false, 'Process Fails', 'Data can\'t Deleted.', '');
                    }
        
                }
            }else{
                    $message = array(false, 'Process Fails', 'Data can\'t Deleted.', '');
    
            }
            echo json_encode($message);
        }else{
            return redirect('./#dashboard');
        }
    }

    private function _parsing($parent_id = 0, $submenu = true) {
        if (isset($this->_temp_menu[$parent_id])) {
            foreach ($this->_temp_menu[$parent_id] as $menu) {
                $menu_id = $menu->menu_id;
                $linkdelete =  url('menu/'.$menu_id);
                $url = $menu->menu_url != '#' ? '#' . $menu->menu_url : $menu->menu_url;
                $menu_icon = $menu->menu_icon;

                $nama_menu = '<div class="dd-handle dd3-handle">&nbsp;</div>';
                $nama_menu .= '<div class="dd3-content">';
                $nama_menu .= '     <i class="fa fa-lg fa-fw ' . $menu_icon . '"></i>  ' . $menu->menu_name . ' <span class="text-info"> - ' . $url . '</span>';
                $nama_menu .= '     <div class="pull-right">';

                if ($this->_access_menu->insert_otoritas_modul) {
                    $nama_menu .= "<a 
                    href='menu/{$menu_id}/add_child' 
                    id='mybutton-add-child-{$menu_id}'
                    class='btn btn-primary btn-xs margin-right-5' 
                    data-toggle='modal' 
                    data-target='#remoteModal'><i class='fa fa-plus'></i></a>";
                }

                if ($this->_access_menu->update_otoritas_modul) {
                    $nama_menu .= "<a 
                        href='menu/{$menu_id}/edit_menu' 
                        id='mybutton-edit-menu-{$menu_id}'
                        class='btn btn-warning btn-xs margin-right-5' 
                        data-toggle='modal' 
                        data-target='#remoteModal'><i class='fa fa-edit'></i></a>";
                }

                if($this->_access_menu->delete_otoritas_modul){
                    $nama_menu .= "<a 
                        href='javascript:void(0);'
                        class='btn btn-danger btn-xs' 
                        id='mybutton-delete-{$menu_id}'
                        onclick='my_data_table.row_action.ajax(this.id)'
                        data-original-title='Delete' 
                        rel='tooltip' 
                        data-url='{$linkdelete}'
                        ><i class='fa fa-trash-o'></i></a>";
                }

                $nama_menu .= '     </div>';
                $nama_menu .= '</div>';

                if ($submenu) {
                    if (isset($this->_temp_menu[$menu_id])) {
                        $this->_html_menu .= '<li class="dd-item dd3-item" data-id="' . $menu_id . '">' . $nama_menu . '';
                        $this->_html_menu .= '  <ol class="dd-list">';
                        $this->_parsing($menu_id);
                        $this->_html_menu .= '  </ol>';
                        $this->_html_menu .= '</li>';
                    } else {
                        $this->_html_menu .= '<li class="dd-item dd3-item" data-id="' . $menu_id . '">' . $nama_menu . '</li>';
                    }
                } else {
                    $this->_html_menu .= '<li class="dd-item dd3-item" data-id="' . $menu_id . '">' . $nama_menu . '</li>';
                }
            }
        }
    }

    private function _list($key = array()) {
        $data_result = modul::orderBy('list_number', 'ASC')->get();

        $temp_menu = array();
        foreach ($data_result as $value) {
            $parent_id = $value->m_id;
            $parent = !empty($parent_id) ? $parent_id : 0;
            $menu_url = '#';

            if (!empty($value->url)) {
                $menu_url = $value->url;
            }

            $temp_menu[$parent][] = (object) array(
                'menu_id' => $value->id,
                'menu_name' => $value->name,
                'menu_icon' => $value->icon,
                'menu_url' => $menu_url
            );
        }
        return $temp_menu;
    }

    private function _admin($key = array()) {
        $this->_temp_menu = $this->_list($key);

        $this->_html_menu = '<ol class="dd-list">';
        $this->_parsing(0);
        $this->_html_menu .= '</ol>';
        return $this->_html_menu;
    }
    
    private function parser_access_menu($_id_role = '')
    {
        $id_user = Auth::user()->id_role;
        $url = $this->_url_data;
        $query = "
            SELECT 
                `a`.`id_menu`,
                `a`.`id_role`,
                `a`.`view_otoritas_modul`,
                `a`.`insert_otoritas_modul`,
                `a`.`update_otoritas_modul`,
                `a`.`delete_otoritas_modul`,
                `a`.`export_otoritas_modul`,
                `a`.`import_otoritas_modul`,
                `a`.`data_otoritas_modul`
            FROM `otoritas_moduls` `a` 
            JOIN `moduls` `b` ON `a`.`id_menu` = `b`.`id` 
            WHERE 
                `a`.`id_role` = '{$id_user}' AND 
                `b`.`url` = '{$url}' ;
        ";
        // echo $query;exit;
        $data  = DB::select($query);
        if ( !empty($data) ){
            $this->_access_menu = $data[0];
            // return json_decode(json_encode($data[0]));
            // return $data;
        }else{
            return false;

        }
    }

    public function proses_ordering(Request $request) {
        
        if ($this->_access_menu->update_otoritas_modul) {
            if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ){

                $nestable = $request->post('nestable_output');
                if (!empty($nestable)) {
                    $this->_ordering(json_decode($nestable));
                    $ordering_data = $this->_temp_ordering;
                    // print_r($ordering_data);exit;
                    $data_error = 0;
                    foreach ($ordering_data as $key => $value) {
                        $updateData = modul::where('id', $key)->firstOrFail();
                        $updateData->list_number = $value['list_number'];
                        $updateData->m_id = $value['m_id'];
                        if ($updateData->save()) {
                        } else {
                            $data_error = $data_error + 1;
                        }
                    }
                    
                    if ($data_error == 0) {
                        $message = array(true, 'Process Successfully', 'The data has been saved.', 'pagefunction()');
                    } else {
                        $message = array('error', 'Error!', 'An error occurred during storage, please try again.', '');
                    }
                } else {
                    $message = array(true, 'Process Successfully', 'The data has been saved.', 'pagefunction()');
                }

                echo json_encode($message);
            }
        } else {
            return abort(404);
        }
    }

    private function _ordering($nestable, $m_id = NULL) {
        if (is_array($nestable)) {
            $menu_order = 1;
            foreach ($nestable as $value) {
                $this->_temp_ordering[$value->id] = array(
                    'list_number' => $menu_order,
                    'm_id' => $m_id
                );

                if (isset($value->children)) {
                    $this->_ordering($value->children, $value->id);
                }
                $menu_order++;
            }
        }
    }
}
