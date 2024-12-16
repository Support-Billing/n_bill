<?php

namespace App\Http\Controllers\monitoring;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ExampleImport;
use App\Services\RecalculateService;
use App\Services\MyService;

use App\Models\monitoring\SettingColom;
use App\Models\monitoring\SettingParser;
use App\Models\monitoring\SettingParserRegex;
use DB;

class FolderFileController extends Controller
{

    private $_page_title = 'Folder Server';
    private $_url_data = 'folderserver';
    private $_id_role = '';
    private $_access_menu;
    private $_myService;
    private $_RecalculateService;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_myService = app(MyService::class);
        $this->_RecalculateService = app(RecalculateService::class);
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
            return view('monitoring.folderfile.index', [
                'page_title' => 'Import Xample ' . $this->_page_title,
                'import_otoritas_modul' => $this->_access_menu->import_otoritas_modul
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }
    
    public function import(int $idx)
    {
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            //
            return view('monitoring.folderfile.import', [
                'page_title' => $this->_page_title,
                'idx' => $idx,
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }

    public function show_all(int $idcore)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            $SettingParser = SettingParser::findOrFail($idcore);
            $SettingColom = SettingColom::get();
            return view('monitoring.folderfile.show_all', [
                'page_title' => $this->_page_title,
                'data_detil' => $SettingParser,
                'setting_colom' => $SettingColom
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function push_import(Request $request, string $idx)
    {
        //
        if($request->ajax())
        {
            // $updateData = bank::findOrFail($id);
            $updateData = SettingParser::where('idx', $idx)->firstOrFail();
            $validation = Validator::make($request->all(), [
                'FileName' => 'required|mimes:xlsx,csv,txt',
                'idx' => 'required',
            ]);

            if ($validation->fails()) {
                $message = array(false, 'Process Fails', $validation->errors(), '');
            }else{
                $message = array('error', 'Error!', 'An error occurred during storage , please try again.', '');
    
                $file = $request->file('FileName');
                
                // if ($request->hasFile('FileName')) {
                //     // Lanjutkan dengan penanganan file
                //     $file = $request->file('FileName');
                //     dd($file);
                // } else {
                //     echo 'data kosong';exit;
                // }
                
                $path = $file->storeAs('uploads', $file->getClientOriginalName());

                // $idx = $request->get('idx');
                // $idx = 7;

                // $dataCollection = collect(Excel::toArray([], storage_path('app/' . $path))[0])->map(function ($item) {
                //     return collect($item);
                // });
                // print_r($dataCollection);exit;
                // Excel::import(new ExampleImport($idx), storage_path('app/' . $path))->get();
                // if (Excel::import(new ExampleImport($idx), storage_path('app/' . $path))){
                //     $message = array(true, 'Process Successful', 'Data updated successfully.', '');
                // }else{
                //     $message = array(false, 'Process Fails', 'The data could not be updated.', '');
                // }

                

                // Dapatkan path real
                $storagePath = storage_path('app/' . $path);

                // Membaca isi file
                $content = file_get_contents($storagePath);
                $lines = explode("\n", $content);

                $line_data_xample = $lines[0];
                $jumlahcolumns = str_getcsv($line_data_xample);
                $jumlahcolumn = count($jumlahcolumns);
                
                $updateData->DataXample = $lines[0];
                $updateData->jumlahcolumn = $jumlahcolumn;
                $updateData->XampleFile = $file->getClientOriginalName();
                
                if ($updateData->save()){
                    $message = array(true, 'Process Successful', 'Data updated successfully.',  'pagefunction()');
                }else{
                    $message = array(false, 'Process Fails', 'The data could not be updated.', '');
                }
            }
    
            echo json_encode($message);
        }else{
            return redirect('./#dashboard');
        }
    }

    public function show_all_table_row(Request $request)
    {

        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            $idx = $request->query('idx');
            $SettingParser = SettingParser::with('setting_parser_regex')->findOrFail($idx);
            $SettingColom = SettingColom::get();
            if(!empty($SettingParser->DataXample)){
                return view('monitoring.folderfile.table_setting_row', [
                    'page_title' => $this->_page_title,
                    'data_detil' => $SettingParser,
                    'setting_colom' => $SettingColom
                ]);
            }else{
                return '
                    <div class="alert alert-info fade in">
				<button class="close" data-dismiss="alert">
					×
				</button>
				<i class="fa-fw fa fa-info"></i>
				<strong>Info!</strong> Xample data belum di import.
			</div>';
            }
        }else{
            return redirect('./#dashboard');
        }
    }
    public function show_all_table_column(Request $request)
    {

        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            $idx = $request->query('idx');
            $SettingParser = SettingParser::with('setting_parser_regex')->findOrFail($idx);
            $SettingColom = SettingColom::get();
            if(!empty($SettingParser->DataXample)){
                return view('monitoring.folderfile.table_setting_column', [
                    'page_title' => $this->_page_title,
                    'data_detil' => $SettingParser,
                    'setting_colom' => $SettingColom
                ]);
            }else{
                return '
                    <div class="alert alert-info fade in">
				<button class="close" data-dismiss="alert">
					×
				</button>
				<i class="fa-fw fa fa-info"></i>
				<strong>Info!</strong> Xample data belum di import.
			</div>';
            }
        }else{
            return redirect('./#dashboard');
        }
    }
    
    private function getcsv_clear_data( $row, $key, $value, $pattern_regex){
        $valuesArray = str_getcsv($row);
        $stringWithoutK  = str_replace('k', '', $value); // untuk mendapatkan integer ganti dengan $numericValueMinusOne = intval(substr($value, 1)) - 1;
        $numericValue = intval($stringWithoutK);
        $numericValueMinusOne = $numericValue - 1;
        $clear_data = $valuesArray[$numericValueMinusOne];
        
        if (!empty($pattern_regex)){
            $clear_data = $this->_RecalculateService->regex_clear_data($clear_data, $pattern_regex);
        }
        $generate_tombol_delete = $this->_myService->generate_tombol_delete($key);
        $return_data = $clear_data.$generate_tombol_delete;

        return $return_data;
    }

    private function getcsv_clear_data_by_regex( $row, $key, $value, $pattern_regex){
        $valuesArray = str_getcsv($row);
        $stringWithoutK  = str_replace('k', '', $value); // untuk mendapatkan integer
        $numericValue = intval($stringWithoutK);
        $numericValueMinusOne = $numericValue - 1;
        $clear_data = $valuesArray[$numericValueMinusOne];
        
        if (!empty($pattern_regex)){
            $clear_data = $this->_RecalculateService->regex_clear_data($clear_data, $pattern_regex);
        }
        $generate_tombol_delete = $this->_myService->generate_tombol_delete($key);
        $return_data = $clear_data.$generate_tombol_delete;

        return $return_data;
    }


    public function update_regex(Request $request, string $idx)
    {
        //
        if($request->ajax())
        {
            $updateData = SettingParser::where('idx', $idx)->firstOrFail();
            $updateData_regex = SettingParserRegex::where('idx_parser', $idx)->firstOrFail();
            $validation = Validator::make($request->all(), [
                'key' => 'required',
                'value' => 'required',
            ]);

            if ($validation->fails()) {
                $message = array(false, 'Process Fails', $validation->errors(), '');
            }else{
                $message = array('error', 'Error!', 'An error occurred during storage , please try again.', '');
    
                $key = $request->get('key');
                $pattern_regex = $request->get('value'); // ini isinya pattern_regex
                
                $updateData_regex->setAttribute('regex_'.$key, $pattern_regex);

                $DataXample = $updateData->DataXample;
                $value = $updateData->$key;

                $getdetail_show = 'Kolom belum di pilih';
                if (!empty($value)){
                    $getdetail_show = $this->getcsv_clear_data_by_regex($DataXample, $key, $value, $pattern_regex);

                }
                
                if ($updateData_regex->save()){
                    $message = array(
                        true,
                        'Process Successful',
                        'Data updated successfully',
                        'key' => $key,
                        'value' => $value,
                        'update_data_id' => 'update_'.$key,
                        'show_data' => $getdetail_show
                    ); 
                }else{
                    $message = array(false, 'Process Fails', 'The data could not be updated.', '');
                }
            }
    
            echo json_encode($message);
        }else{
            return redirect('./#dashboard');
        }

    }

    public function update_parser(Request $request, string $idx)
    {
        //
        if($request->ajax())
        {
            $updateData = SettingParser::where('idx', $idx)->firstOrFail();
            $validation = Validator::make($request->all(), [
                'key' => 'required',
                'value' => 'required',
            ]);

            if ($validation->fails()) {
                $message = array(false, 'Process Fails', $validation->errors(), '');
            }else{
                $message = array('error', 'Error!', 'An error occurred during storage , please try again.', '');
    
                $value = $request->get('value'); 
                
                $updateData->teknik_parser = $value;
                
                if ($updateData->save()){
                    $message = array(
                        true,
                        'Process Successful',
                        'Data updated successfully'
                    ); 
                }else{
                    $message = array(false, 'Process Fails', 'The data could not be updated.', '');
                }
            }
    
            echo json_encode($message);
        }else{
            return redirect('./#dashboard');
        }

    }

    public function update(Request $request, string $idx)
    {
        //
        if($request->ajax())
        {
            // $updateData = bank::findOrFail($id);
            $updateData = SettingParser::where('idx', $idx)->firstOrFail();
            $updateData_regex = SettingParserRegex::where('idx_parser', $idx)->firstOrFail();
            $validation = Validator::make($request->all(), [
                'key' => 'required',
                'value' => 'required',
            ]);

            if ($validation->fails()) {
                $message = array(false, 'Process Fails', $validation->errors(), '');
            }else{
                $message = array('error', 'Error!', 'An error occurred during storage , please try again.', '');
    
                $key = $request->get('key');
                $value = $request->get('value');
                if ($value == 'delete') {
                    $updateData->setAttribute($key, Null);
                }else{
                    $updateData->setAttribute($key, $value);
                }

                $getdetail_show = '';
                if ($value != 'delete') {
                    $DataXample = $updateData->DataXample;
                    $key_regex = 'regex_'.$key;
                    $pattern_regex = $updateData_regex->$key_regex;
                    $getdetail_show = $this->getcsv_clear_data($DataXample, $key, $value, $pattern_regex);
                }

                if ($updateData->save()){
                    $message = array(
                        true,
                        'Process Successful',
                        'Data updated successfully',
                        'key' => $key,
                        'value' => $value,
                        'update_data_id' => 'update_'.$key,
                        'show_data' => $getdetail_show
                    ); 
                }else{
                    $message = array(false, 'Process Fails', 'The data could not be updated.', '');
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

            if (!empty($data_cond['keyword'])) {
                $cond[] = ['name', 'LIKE', '%' . $data_cond['keyword'] . '%'];
            }

            $data_result = SettingParser::where($cond)->offset($offset)->limit($limit)->get();
            $data_count = SettingParser::where($cond)->count();


            $rows = array();
            $no = $offset;

            foreach ($data_result as $value) {
                $id = $value->idx;

                $action = "<a href='javascript:void(0);' 
                    class='btn bg-color-orange btn-xs margin-right-5 text-white' 
                    id='mybutton-show-{$id}' 
                    data-breadcrumb='View' 
                    onclick='my_form.open(this.id)' 
                    data-module='folderfile' 
                    data-url='folderfile/{$id}/show_all' 
                    data-original-title='View' 
                    rel='tooltip'
                    data-placement='left'><i class='fa fa-eye'></i></a>";
                
                $no++;
                $rows[] = array(
                    $no,
                    $value->TypeServer,
                    $value->XampleFile,
                    $value->jumlahcolumn,
                    $value->status_parser,
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
