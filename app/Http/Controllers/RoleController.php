<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\OtoritasModul;
use App\Models\Modul;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\str;
use DB;

class RoleController extends Controller
{

    private $_page_title = 'Role';
    private $_temp_menu = array();
    private $_temp_ordering = array();
    private $_html_menu = '';
    private $_authorities = array(
        'view_otoritas_modul' => array(),
        'insert_otoritas_modul' => array(),
        'update_otoritas_modul' => array(),
        'delete_otoritas_modul' => array(),
        'export_otoritas_modul' => array(),
        'import_otoritas_modul' => array()
    );
    private $_access_menu;
    private $_url_data = 'role';
    private $_id_role = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $_id_role = Auth::user()->id_role;
            $this->parser_access_menu($_id_role);

            return $next($request);
        });
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('content.role.index', [
            'page_title' => $this->_page_title,
            'insert_otoritas_modul' => $this->_access_menu->insert_otoritas_modul
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        $data_menu = $this->_admin();
        return view('content.role.create', [
            'page_title' => 'role',
            'data_menu' => $data_menu
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validation = Validator::make($request->all(), [
            "name" => ['required', 'min:3', 'max:255'],
            "description" => ['required', 'min:3', 'max:255']
        ]);
        
        if ($validation->fails()) {
            $message = array(false, 'Process Fails', $validation->errors(), '');
        }else{
            
            $name = $request->get('name');
            $description = $request->get('description');

            $newData = new role;
            $id_role = str::Uuid(36);
            $newData->id = $id_role;
            $newData->name = $name;
            $newData->description = $description;
            if ($newData->save()){
                
                // deklarasi defailt data dari post data 
                $otoritas_menu_view = $request->get('otoritas_menu_view');
                $otoritas_menu_add = $request->get('otoritas_menu_add');
                $otoritas_menu_edit = $request->get('otoritas_menu_edit');
                $otoritas_menu_delete = $request->get('otoritas_menu_delete');
                $otoritas_menu_approve = $request->get('otoritas_menu_approve');
                $otoritas_menu_export = $request->get('otoritas_menu_export');
                $otoritas_menu_import = $request->get('otoritas_menu_import');
                $otoritas_menu_otordata = $request->get('otoritas_menu_otordata');
                
                $data = array(
                    'otoritas_menu_view' => $otoritas_menu_view,
                    'otoritas_menu_add' => $otoritas_menu_add,
                    'otoritas_menu_edit' => $otoritas_menu_edit,
                    'otoritas_menu_delete' => $otoritas_menu_delete,
                    'otoritas_menu_approve' => $otoritas_menu_approve,
                    'otoritas_menu_export' => $otoritas_menu_export,
                    'otoritas_menu_import' => $otoritas_menu_import,
                    'otoritas_menu_otordata' => $otoritas_menu_otordata
                );

                // $authorities = $this->_parsing_authorities($otoritas_menu_view, $otoritas_menu_add, $otoritas_menu_edit, $otoritas_menu_delete, $otoritas_menu_approve, $otoritas_menu_export, $otoritas_menu_import, $otoritas_menu_otordata);
                $authorities = $this->_parsing_authorities($data);
                
                foreach ($authorities as $key => $value) {
                    $data_insert[] = array(
                        'id_menu' => (string) $key,
                        'id_role' => $id_role,
                        'view_otoritas_modul' => isset($value['view_otoritas_modul']) ? $value['view_otoritas_modul'] : '0',
                        'insert_otoritas_modul' => isset($value['insert_otoritas_modul']) ? $value['insert_otoritas_modul'] : '0',
                        'update_otoritas_modul' => isset($value['update_otoritas_modul']) ? $value['update_otoritas_modul'] : '0',
                        'delete_otoritas_modul' => isset($value['delete_otoritas_modul']) ? $value['delete_otoritas_modul'] : '0',
                        'import_otoritas_modul' => isset($value['import_otoritas_modul']) ? $value['import_otoritas_modul'] : '0',
                        'export_otoritas_modul' => isset($value['export_otoritas_modul']) ? $value['export_otoritas_modul'] : '0',
                        'data_otoritas_modul' => isset($value['data_otoritas_modul']) ? $value['data_otoritas_modul'] : null
                    );
                }
                OtoritasModul::insert($data_insert);
                // $this->role_authorities_model->create($data);

                $message = array(true, 'Process Successfully', 'The data has been saved.', 'my_form.reset(\'#finput\')');
                
            }else{
                $message = array(false, 'Process Fails', 'The data could not be saved.', '');
            }
        }

        echo json_encode($message);
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
        //
        $role = Role::findOrFail($id);
        $this->_authorities($id);
        $data_menu = $this->_admin();
        return view('content.role.edit', [
            'page_title' => 'role',
            'data' => $role,
            'data_menu' => $data_menu,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $updateData = Role::findOrFail($id);
        $validation = Validator::make($request->all(), [
            "name" => ['required', 'min:3', 'max:255'],
            "description" => ['required', 'min:3', 'max:255']
        ]);
        
        if ($validation->fails()) {
            $message = array(false, 'Process Fails', $validation->errors(), '');
        }else{
            
            $message = array('error', 'Error!', 'An error occurred during storage , please try again.', '');
    
            $name = $request->get('name');
            $description = $request->get('description');

            $updateData->name = $name;
            $updateData->description = $description;
            
            if ($updateData->save()){

                $otoritas_menu_view = $request->get('otoritas_menu_view');
                $otoritas_menu_add = $request->get('otoritas_menu_add');
                $otoritas_menu_edit = $request->get('otoritas_menu_edit');
                $otoritas_menu_delete = $request->get('otoritas_menu_delete');
                $otoritas_menu_approve = $request->get('otoritas_menu_approve');
                $otoritas_menu_export = $request->get('otoritas_menu_export');
                $otoritas_menu_import = $request->get('otoritas_menu_import');
                $otoritas_menu_otordata = $request->get('otoritas_menu_otordata');
                
                $data = array(
                    'otoritas_menu_view' => $otoritas_menu_view,
                    'otoritas_menu_add' => $otoritas_menu_add,
                    'otoritas_menu_edit' => $otoritas_menu_edit,
                    'otoritas_menu_delete' => $otoritas_menu_delete,
                    'otoritas_menu_approve' => $otoritas_menu_approve,
                    'otoritas_menu_export' => $otoritas_menu_export,
                    'otoritas_menu_import' => $otoritas_menu_import,
                    'otoritas_menu_otordata' => $otoritas_menu_otordata
                );
                // $authorities = $this->_parsing_authorities($otoritas_menu_view, $otoritas_menu_add, $otoritas_menu_edit, $otoritas_menu_delete, $otoritas_menu_approve, $otoritas_menu_export, $otoritas_menu_import, $otoritas_menu_otordata);
                $authorities = $this->_parsing_authorities($data);
                
                $deleted = OtoritasModul::where('id_role', $id)->delete();
                if(!empty($deleted)){
                    
                    foreach ($authorities as $key => $value) {
                        $data_insert[] = array(
                            'id_menu' => (string) $key,
                            'id_role' => $id,
                            'view_otoritas_modul' => isset($value['view_otoritas_modul']) ? $value['view_otoritas_modul'] : '0',
                            'insert_otoritas_modul' => isset($value['insert_otoritas_modul']) ? $value['insert_otoritas_modul'] : '0',
                            'update_otoritas_modul' => isset($value['update_otoritas_modul']) ? $value['update_otoritas_modul'] : '0',
                            'delete_otoritas_modul' => isset($value['delete_otoritas_modul']) ? $value['delete_otoritas_modul'] : '0',
                            'import_otoritas_modul' => isset($value['import_otoritas_modul']) ? $value['import_otoritas_modul'] : '0',
                            'export_otoritas_modul' => isset($value['export_otoritas_modul']) ? $value['export_otoritas_modul'] : '0',
                            'data_otoritas_modul' => isset($value['data_otoritas_modul']) ? $value['data_otoritas_modul'] : null
                        );
                    }
                    OtoritasModul::insert($data_insert);
                    $message = array(true, 'Process Successful', 'Data updated successfully.', '');
                }else{
                    $message = array(false, 'Process Fails', 'The data could not be updated.', '');
                }
                
            }else{
                $message = array(false, 'Process Fails', 'The data could not be updated.', '');
            }
        }

        echo json_encode($message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function load(Request $request)
    {
            # Data Table
            $limit = $request->get('length');
            $offset = $request->get('start');
            $draw = $request->get('draw');
            $extra_search = $request->get('extra_search');
            $data_cond = $this->seriliaze_decode($extra_search);

            # Condition
            $cond = array();

            if (!empty($data_cond['keyword'])) {
                $cond[] = ['name', 'LIKE', '%' . $data_cond['keyword'] . '%'];
            }

            $data_result = Role::where($cond)->offset($offset)->limit($limit)->get();
            $data_count = Role::where($cond)->count();


            $rows = array();
            $no = $offset;

            foreach ($data_result as $value) {
                $id = $value->id;
                $linkdelete =  url('worklocation/'.$id);

                $action = '<div class="text-align-center">';

                $action .= "<a href='javascript:void(0);' 
                    class='btn btn-warning btn-xs margin-right-5' 
                    id='mybutton-edit-{$id}' 
                    data-breadcrumb='Edit' 
                    onclick='my_form.open(this.id)' 
                    data-module='role' 
                    data-url='role/{$id}/edit' 
                    data-original-title='Edit' 
                    rel='tooltip' 
                    data-placement='left'><i class='fa fa-edit'></i></a>";

                $action .= "<a 
                            href='javascript:void(0);'
                            class='btn btn-danger btn-xs' 
                            id='mybutton-delete-{$id}'
                            onclick='my_data_table.row_action.ajax(this.id)'
                            data-original-title='Delete' 
                            rel='tooltip' 
                            data-url='{$linkdelete}'
                            ><i class='fa fa-trash-o'></i></a>";

                $no++;
                $rows[] = array(
                    $no,
                    $value->name,
                    $value->description,
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
    }

    private function seriliaze_decode($array = array())
    {

        $dec = array();

        if (is_array($array)) {
            foreach ($array as $value) {
                $dec[$value['name']] = $value['value'];
            }
        }

        return $dec;
    }

    private function _parsing_authorities($data)
    {
        
        // Parsing roles
        $authorities = array();

        if (isset($data['otoritas_menu_view']) && is_array($data['otoritas_menu_view'])) {
            foreach ($data['otoritas_menu_view'] as $value) {
                $authorities["{$value}"]['view_otoritas_modul'] = '1';
            }
        }

        if (isset($data['otoritas_menu_add']) && is_array($data['otoritas_menu_add'])) {
            foreach ($data['otoritas_menu_add'] as $value) {
                $authorities["{$value}"]['insert_otoritas_modul'] = '1';
            }
        }

        if (isset($data['otoritas_menu_edit']) && is_array($data['otoritas_menu_edit'])) {
            foreach ($data['otoritas_menu_edit'] as $value) {
                $authorities["{$value}"]['update_otoritas_modul'] = '1';
            }
        }

        if (isset($data['otoritas_menu_delete']) && is_array($data['otoritas_menu_delete'])) {
            foreach ($data['otoritas_menu_delete'] as $value) {
                $authorities["{$value}"]['delete_otoritas_modul'] = '1';
            }
        }

        if (isset($data['otoritas_menu_export']) && is_array($data['otoritas_menu_export'])) {
            foreach ($data['otoritas_menu_export'] as $value) {
                $authorities["{$value}"]['export_otoritas_modul'] = '1';
            }
        }

        if (isset($data['otoritas_menu_import']) && is_array($data['otoritas_menu_import'])) {
            foreach ($data['otoritas_menu_import'] as $value) {
                $authorities["{$value}"]['import_otoritas_modul'] = '1';
            }
        }
        
        if (isset($data['otoritas_menu_otordata']) && is_array($data['otoritas_menu_otordata'])) {
            foreach ($data['otoritas_menu_otordata'] as $key => $value) {
                $authorities["{$key}"]['data_otoritas_modul'] = $value;
            }
        }

        return $authorities;

    }

    private function _authorities($id = '') {
        // $data = $this->role_authorities_model->data($key)->get();

        $cond[] = ['id_role', $id];
        $data_result = OtoritasModul::where($cond)->get();
        foreach ($data_result as $value) {
            if ($value->view_otoritas_modul == '1')
                $this->_authorities['view_otoritas_modul'][] = $value->id_menu;
            if ($value->insert_otoritas_modul == '1')
                $this->_authorities['insert_otoritas_modul'][] = $value->id_menu;
            if ($value->update_otoritas_modul == '1')
                $this->_authorities['update_otoritas_modul'][] = $value->id_menu;
            if ($value->delete_otoritas_modul == '1')
                $this->_authorities['delete_otoritas_modul'][] = $value->id_menu;
            if ($value->export_otoritas_modul == '1')
                $this->_authorities['export_otoritas_modul'][] = $value->id_menu;
            if ($value->import_otoritas_modul == '1')
                $this->_authorities['import_otoritas_modul'][] = $value->id_menu;
            
            $this->_authorities['data_otoritas_modul'][(string)$value->id_menu] = $value->data_otoritas_modul;
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

    private function _parsing($parent_id = 0, $submenu = true) {
        if (isset($this->_temp_menu[$parent_id])) {
            foreach ($this->_temp_menu[$parent_id] as $menu) {
                // print_r($menu);exit;
                $id_menu = '' ;
                $url = '' ;
                $icon_menu = '' ;
                $id_menu = $menu->menu_id;
                $url = $menu->menu_url != '#' ? '#' . $menu->menu_url : $menu->menu_url;
                $icon_menu = $menu->menu_icon;

                $nama_menu = '<div class="dd-handle dd3-handle dd-nodrag">&nbsp;</div>';
                $nama_menu .= '<div class="dd3-content">';
                $nama_menu .= '     <i class="fa fa-lg fa-fw ' . $icon_menu . '"></i>  ' . $menu->menu_name . ' <span class="text-info"> - ' . $url . '</span>';
                $nama_menu .= '     <div class="pull-right">';

                $nama_menu .= '         <div class="checkbox no-margin no-padding">';

                $checked = '';
                if (in_array($id_menu, $this->_authorities['view_otoritas_modul'])) {
                    $checked = "checked='checked'";
                }
                $nama_menu .= '<label>' . 
                    "<input type='checkbox' name='otoritas_menu_view[{$id_menu}]' value='{$id_menu}' {$checked} class='checkbox style-0 cb_select cb_sb_view_otoritas_modul{$parent_id}' target-selected='cb_sb_view_otoritas_modul{$id_menu}' id='cb_view_otoritas_modul{$id_menu}' onchange='my_global.select_all(this.id)'>". 
                        '<span class="font-xs"><i class="fa fa-eye"></i></span></label>';

                $checked = '';
                if (in_array($id_menu, $this->_authorities['insert_otoritas_modul'])) {
                    $checked = "checked='checked'";
                }
                $nama_menu .= '<label>' . 
                    "<input type='checkbox' name='otoritas_menu_add[{$id_menu}]' value='{$id_menu}' {$checked} class='checkbox style-0 cb_select cb_sb_view_otoritas_modul{$id_menu} cb_sb_view_otoritas_modul{$parent_id} cb_sb_insert_otoritas_modul{$parent_id}' target-selected='cb_sb_insert_otoritas_modul{$id_menu}' id='cb_insert_otoritas_modul{$id_menu}' onchange='my_global.select_all(this.id)'>". 
                        '<span class="font-xs"><i class="fa fa-plus"></i></span></label>';

                $checked = '';
                if (in_array($id_menu, $this->_authorities['update_otoritas_modul'])) {
                    $checked = "checked='checked'";
                }
                $nama_menu .= '<label>' . 
                    "<input type='checkbox' name='otoritas_menu_edit[{$id_menu}]' value='{$id_menu}' {$checked} class='checkbox style-0 cb_select cb_sb_view_otoritas_modul{$id_menu} cb_sb_view_otoritas_modul{$parent_id} cb_sb_update_otoritas_modul{$parent_id}' target-selected='cb_sb_update_otoritas_modul{$id_menu}' id='cb_update_otoritas_modul{$id_menu}' onchange='my_global.select_all(this.id)'>". 
                        '<span class="font-xs"><i class="fa fa-edit"></i></span></label>';

                $checked = '';
                if (in_array($id_menu, $this->_authorities['delete_otoritas_modul'])) {
                    $checked = "checked='checked'";
                }
                $nama_menu .= '<label>' . 
                    "<input type='checkbox' name='otoritas_menu_delete[{$id_menu}]' value='{$id_menu}' {$checked} class='checkbox style-0 cb_select cb_sb_view_otoritas_modul{$id_menu} cb_sb_view_otoritas_modul{$parent_id} cb_sb_delete_otoritas_modul{$parent_id}' target-selected='cb_sb_delete_otoritas_modul{$id_menu}' id='cb_update_otoritas_modul{$id_menu}' onchange='my_global.select_all(this.id)'>". 
                        '<span class="font-xs"><i class="fa fa-trash-o"></i></span></label>';

                $checked = '';
                if (in_array($id_menu, $this->_authorities['export_otoritas_modul'])) {
                    $checked = "checked='checked'";
                }
                $nama_menu .= '<label>' . 
                    "<input type='checkbox' name='otoritas_menu_export[{$id_menu}]' value='{$id_menu}' {$checked} class='checkbox style-0 cb_select cb_sb_view_otoritas_modul{$id_menu} cb_sb_view_otoritas_modul{$parent_id} cb_sb_export_otoritas_modul{$parent_id}' target-selected='cb_sb_export_otoritas_modul{$id_menu}' id='cb_sb_export_otoritas_modul{$id_menu}' onchange='my_global.select_all(this.id)'>". 
                        '<span class="font-xs"><i class="fa fa-download"></i></span></label>';

                $checked = '';
                if (in_array($id_menu, $this->_authorities['import_otoritas_modul'])) {
                    $checked = "checked='checked'";
                }
                $nama_menu .= '<label>' . 
                    "<input type='checkbox' name='otoritas_menu_import[{$id_menu}]' value='{$id_menu}' {$checked} class='checkbox style-0 cb_select cb_sb_view_otoritas_modul{$id_menu} cb_sb_view_otoritas_modul{$parent_id} cb_sb_import_otoritas_modul{$parent_id}' target-selected='cb_sb_import_otoritas_modul{$id_menu}' id='cb_sb_import_otoritas_modul{$id_menu}' onchange='my_global.select_all(this.id)'>". 
                        '<span class="font-xs"><i class="fa fa-upload"></i></span></label>';

                $data_otoritas_modul = '';
                if (isset($this->_authorities['data_otoritas_modul'][$id_menu])) {
                    $this->_authorities['data_otoritas_modul'][$id_menu];
                }
                $nama_menu .= "<label class='no-padding'>
                <select name='otoritas_menu_otordata[{$id_menu}]' class='form-control dd_roles dd_roles{$parent_id}' id='opt_otoritas_data{$id_menu}' target-selected='dd_roles{$id_menu}' onchange='my_global.set_value_selected(this.id)'>
                        <option value='1'>Self</option>
                        <option value='2'>Group</option>
                        <option value='3' selected='selected'>All</option>
                </select>";
                $nama_menu .= '         </div>';

                $nama_menu .= '     </div>';
                $nama_menu .= '</div>';

                if ($submenu) {
                    if (isset($this->_temp_menu[$id_menu])) {
                        $this->_html_menu .= '<li class="dd-item dd3-item" data-id="' . $id_menu . '">' . $nama_menu . '';
                        $this->_html_menu .= '  <ol class="dd-list">';
                        $this->_parsing($id_menu);
                        $this->_html_menu .= '  </ol>';
                        $this->_html_menu .= '</li>';
                    } else {
                        $this->_html_menu .= '<li class="dd-item dd3-item" data-id="' . $id_menu . '">' . $nama_menu . '</li>';
                    }
                } else {
                    $this->_html_menu .= '<li class="dd-item dd3-item" data-id="' . $id_menu . '">' . $nama_menu . '</li>';
                }
            }
        }
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
        
        $data  = DB::select($query);
        if ( !empty($data) ){
            $this->_access_menu = $data[0];
        }else{
            return false;

        }
    }

}
