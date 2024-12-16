<?php

namespace App\Http\Controllers\monitoring;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Services\MyService;
use App\Services\CdrKolomService;
use App\Models\monitoring\RepairCdr;
use App\Models\monitoring\SettingParser;
use DB;

class RepairCdrController extends Controller
{
    
    private $_page_title = 'Folder Server';
    private $_url_data = 'folderserver';
    private $_access_menu;
    private $_myService;
    private $_SettingParsersMera ;
    private $_SettingParsersVos ;
    private $_SettingParsersDirect ;
    private $_CdrKolomService;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_myService = app(MyService::class);
        $this->_CdrKolomService = app(CdrKolomService::class);
        $this->middleware('auth');
        $this->getSettingParser();
        $this->middleware(function ($request, $next) {
            $this->_access_menu = $this->_myService->parser_access_menu($this->_url_data);
            return $next($request);
        });
    }
    
    public function getSettingParser() {
        $SettingParsers = SettingParser::with('setting_parser_regex', 'setting_parser_row')->get();
        
        if ($SettingParsers->isNotEmpty()) {
            $this->SettingParsersMera = $SettingParsers[0];
            $this->SettingParsersVos = $SettingParsers[1];
            $this->SettingParsersDirect = $SettingParsers[2]; // ASTERISK / ELASTIX
        }
        
        return 'succsess';
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            return view('monitoring.repaircdr.index', [
                'page_title' => $this->_page_title,
                'import_otoritas_modul' => $this->_access_menu->import_otoritas_modul
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
                $cond[] = ['name', 'LIKE', '%' . $data_cond['keyword'] . '%'];
            }

            $data_result = RepairCdr::where($cond)->offset($offset)->limit($limit)->get();
            $data_count = RepairCdr::where($cond)->count();


            $rows = array();
            $no = $offset;

            foreach ($data_result as $value) {
                $id = $value->idx;
                $action = "<a href='javascript:void(0);' 
                    class='btn bg-color-orange btn-xs margin-right-5' 
                    id='mybutton-show-{$id}' 
                    data-breadcrumb='View' 
                    onclick='my_form.open(this.id)' 
                    data-module='customer' 
                    data-url='repaircdr/{$id}/show_all' 
                    data-original-title='View' 
                    rel='tooltip'
                    data-placement='left'><i class='fa fa-eye'></i></a>";
                
                $no++;
                $rows[] = array(
                    $no,
                    $value->foldername,
                    $value->filename,
                    $value->LineNumber,
                    $value->reasonCode,
                    $value->datetime,
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

    

    /* ***** Kebutuhan 9 kolom ***** */
    public function repair_kolom(string $idcore)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            return view('monitoring.repaircdr.repair_kolom', [
                'page_title' => $this->_page_title
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }

    
    public function reason_kolom()
    {
        
        # Condition
        $cond = array();
        $data_result = RepairCdr::where($cond)
            ->where(function($query) {
                $query->whereNull('datetime');
            })
            ->where(function($query2) {
                $query2->WhereNull('reasonCode');
            })
            ->limit(3000)
            ->get();

        if (!$data_result->isEmpty() && $data_result->count() >= 1 ) {
            
            foreach ($data_result as $value) {
                
                $setting_parser = array();
                $dataCallback = array();

                $idx = $value->idx;
                $valuesString = $value->data;
                $valuesArray = str_getcsv($value->data);
                $jmlKolPHP = count($valuesArray);
                
                switch ($value->serverData) {
                    case 'MERA':
                        $setting_parser = $this->SettingParsersMera;

                        // datetime
                        $datetime = $this->_CdrKolomService->datetimeMERA($valuesArray, $valuesString, $setting_parser);
                        // sourceNo
                        $sourceNo = $this->_CdrKolomService->generalKolomMERA($valuesArray, $valuesString, $setting_parser, 'sourceNo');
                        // sourceNoOut
                        $sourceNoOut = $this->_CdrKolomService->generalKolomMERA($valuesArray, $valuesString, $setting_parser, 'sourceNoOut');
                        // sourceIP
                        $sourceIP = $this->_CdrKolomService->generalKolomMERA($valuesArray, $valuesString, $setting_parser, 'sourceIP');
                        // elapsedTime
                        $elapsedTime = $this->_CdrKolomService->generalKolomMERA($valuesArray, $valuesString, $setting_parser, 'elapsedTime');
                        // destNo
                        $destNo = $this->_CdrKolomService->generalKolomMERA($valuesArray, $valuesString, $setting_parser, 'destNo');
                        // destNoOut
                        $destNoOut = $this->_CdrKolomService->generalKolomMERA($valuesArray, $valuesString, $setting_parser, 'destNoOut');
                        // destIP
                        $destIP = $this->_CdrKolomService->generalKolomMERA($valuesArray, $valuesString, $setting_parser, 'destIP');
                        // destName
                        $destName = $this->_CdrKolomService->generalKolomMERA($valuesArray, $valuesString, $setting_parser, 'destName');
                        // destIPOnly
                        $destIPOnly = $this->_CdrKolomService->generalKolomMERA($valuesArray, $valuesString, $setting_parser, 'destIPOnly');
                        // sourceIPOnly
                        $sourceIPOnly = $this->_CdrKolomService->generalKolomMERA($valuesArray, $valuesString, $setting_parser, 'sourceIPOnly');
                        
                        break;
                    case 'VOS':
                        $setting_parser = $this->SettingParsersVos;
                        
                        // datetime
                        $datetime = $this->_CdrKolomService->datetimeVOS($valuesArray, $setting_parser);
                        // sourceNo
                        // $sourceNo = $this->_CdrKolomService->sourceNoVOS($valuesArray, $setting_parser);
                        $sourceNo = $this->_CdrKolomService->generalKolomVOS($valuesArray, $setting_parser, 'sourceNo');
                        // sourceNoOut
                        $sourceNoOut = $this->_CdrKolomService->generalKolomVOS($valuesArray, $setting_parser, 'sourceNoOut');
                        // sourceIP
                        $sourceIP = $this->_CdrKolomService->generalKolomVOS($valuesArray, $setting_parser, 'sourceIP');
                        // elapsedTime
                        $elapsedTime = $this->_CdrKolomService->generalKolomVOS($valuesArray, $setting_parser, 'elapsedTime');
                        // destNo
                        $destNo = $this->_CdrKolomService->generalKolomVOS($valuesArray, $setting_parser, 'destNo');
                        // destNoOut
                        $destNoOut = $this->_CdrKolomService->generalKolomVOS($valuesArray, $setting_parser, 'destNoOut');
                        // destIP
                        $destIP = $this->_CdrKolomService->generalKolomVOS($valuesArray, $setting_parser, 'destIP');
                        // destName
                        $destName = $this->_CdrKolomService->generalKolomVOS($valuesArray, $setting_parser, 'destName');
                        // destIPOnly
                        $destIPOnly = $this->_CdrKolomService->generalKolomVOS($valuesArray, $setting_parser, 'destIPOnly');
                        // sourceIPOnly
                        $sourceIPOnly = $this->_CdrKolomService->generalKolomVOS($valuesArray, $setting_parser, 'sourceIPOnly');
                        
                        break;
                    case 'ELASTIX':
                        $setting_parser = $this->SettingParsersDirect;
                        
                        // datetime
                        $datetime = $this->_CdrKolomService->datetimeDirect($valuesArray, $setting_parser, 'datetime');
                        // sourceNo
                        $sourceNo = $this->_CdrKolomService->sourceNoDirect($valuesArray, $setting_parser, 'sourceNo');
                        // sourceNoOut
                        $sourceNoOut = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'sourceNoOut');
                        // sourceIP
                        $sourceIP = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'sourceIP');
                        // elapsedTime
                        $elapsedTime = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'elapsedTime');
                        // destNo
                        $destNo = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'destNo');
                        // destNoOut
                        $destNoOut = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'destNoOut');
                        // destIP
                        $destIP = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'destIP');
                        // destName
                        $destName = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'destName');
                        // destIPOnly
                        $destIPOnly = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'destIPOnly');
                        // sourceIPOnly
                        $sourceIPOnly = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'sourceIPOnly');
                        
                        break;
                    case 'ASTERISK':
                        $setting_parser = $this->SettingParsersDirect;

                        // datetime
                        $datetime = $this->_CdrKolomService->datetimeDirect($valuesArray, $setting_parser, 'datetime');
                        // sourceNo
                        $sourceNo = $this->_CdrKolomService->sourceNoDirect($valuesArray, $setting_parser, 'sourceNo');
                        // sourceNoOut
                        $sourceNoOut = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'sourceNoOut');
                        // sourceIP
                        $sourceIP = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'sourceIP');
                        // elapsedTime
                        $elapsedTime = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'elapsedTime');
                        // destNo
                        $destNo = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'destNo');
                        // destNoOut
                        $destNoOut = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'destNoOut');
                        // destIP
                        $destIP = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'destIP');
                        // destName
                        $destName = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'destName');
                        // destIPOnly
                        $destIPOnly = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'destIPOnly');
                        // sourceIPOnly
                        $sourceIPOnly = $this->_CdrKolomService->generalKolomDirect($valuesArray, $setting_parser, 'sourceIPOnly');
                        
                        break;
                }

                if (!is_numeric($elapsedTime)) {
                    $call_data_server = array(
                        'idx' => $idx,
                        'datetime' => $datetime,
                        'sourceNo' => $sourceNo,
                        'sourceNoOut' => $sourceNoOut,
                        'sourceIP' => $sourceIP,
                        'elapsedTime' => 0,
                        'destNo' => $destNo,
                        'destNoOut' => $destNoOut,
                        'destIP' => $destIP,
                        'destName' => $destName,
                        'destIPOnly' => $destIPOnly,
                        'sourceIPOnly' => $sourceIPOnly,
                        'reasonCode' => 'not'
                    );

                }else{
                    $call_data_server = array(
                        'idx' => $idx,
                        'datetime' => $datetime,
                        'sourceNo' => $sourceNo,
                        'sourceNoOut' => $sourceNoOut,
                        'sourceIP' => $sourceIP,
                        'elapsedTime' => $elapsedTime,
                        'destNo' => $destNo,
                        'destNoOut' => $destNoOut,
                        'destIP' => $destIP,
                        'destName' => $destName,
                        'destIPOnly' => $destIPOnly,
                        'sourceIPOnly' => $sourceIPOnly,
                        'reasonCode' => Null
                    );

                }
                $dataToUpdate[] = $call_data_server;
            }
            
            $result = RepairCdr::upsert($dataToUpdate, ['idx'], ['datetime', 'sourceNo','sourceNoOut','sourceIP','elapsedTime','destNo','destNoOut','destIP','destName', 'destIPOnly', 'sourceIPOnly']);
            if ($result > 0) {
                $message = array(true, 'Process Successful', 'Data updated successfully.', 'nextload(\'Yes\')');
            }else{
                $message = array(false, 'Process Fails', 'The data could not be updated.', '');
            }
            
        }else{
            $message = array(true, 'Process Successful', 'All Data updated successfully.', 'nextload(\'no\')');
        }
        echo json_encode($message);
    }
    /* ***** end 9 kolom ***** */
    

    /* ***** Kebutuhan move kolom ***** */
    public function readymovecdr(string $idcore)
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            return view('monitoring.repaircdr.move_kolom', [
                'page_title' => $this->_page_title
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }

    
    public function movecdr()
    {
        
        // Kondisi untuk memilih data dari table a
        $condition = [ 
            // ['kolom_a', '=', 'nilai_a'], // contoh kondisi
            // ['kolom_b', '>', 100],       // contoh kondisi
            ['datetime', '!=', null],      // kondisi untuk datetime yang null
            // ['reasonCode', '!=', null],    // kondisi untuk reasonCode yang null
            // tambahkan kondisi lainnya sesuai kebutuhan
        ];

        // Jumlah baris yang ingin dipindahkan
        $limit = 5000;

        // Memindahkan data dari table a ke table b dengan kondisi dan limit
        $inserted = DB::table('cdr')->insertUsing(
            ['idxCore', 'from', 'idx',
                'foldername',
                'filename',
                'ReadyPath',
                'ResultPath',
                'DateTimeCSVtoDB',
                'LineNumber',
                'serverData',
                'jmlKol',
                'jmlKolPHP',
                'data',
                'datetime',
                'sourceNo',
                'sourceNoOut',
                'sourceIP',
                'elapsedTime',
                'destNo',
                'destNoOut',
                'destIP',
                'destName',
                'sourceIPValue',
                'destIPValue',
                'sourceIPFixed',
                'destIPFixed',
                'idxCustomer',
                'idxCustomerIP',
                'idxCustomerIPPrefix',
                'destNoCustPrefix',
                'destNoPrefix',
                'destNoCust',
                'destNoPrefixName',
                'idxSupplier',
                'idxSupplierIP',
                'idxSupplierIPPrefix',
                'destNoSuppPrefix',
                'destNoSupplier',
                'destNoSupplierPrefix',
                'destNoSupplierPrefixName',
                'destNoRealPrefix',
                'destNoRealPrefixName',
                'custTime',
                'custPrice',
                'supplierPrice',
                'supplierTime',
                'destIPOnly',
                'sourceIPOnly',
                'created',
                'modified',
                'idxServer',
                'reasonCode'
            ],
            function ($query) use ($condition, $limit) {
                $uuid = (string) Str::uuid();
                $query->select(DB::raw("UUID()"), DB::raw("'Repair CDR'"), 'idx',
                'foldername',
                'filename',
                'ReadyPath',
                'ResultPath',
                'DateTimeCSVtoDB',
                'LineNumber',
                'serverData',
                'jmlKol',
                'jmlKolPHP',
                'data',
                'datetime',
                'sourceNo',
                'sourceNoOut',
                'sourceIP',
                'elapsedTime',
                'destNo',
                'destNoOut',
                'destIP',
                'destName',
                'sourceIPValue',
                'destIPValue',
                'sourceIPFixed',
                'destIPFixed',
                'idxCustomer',
                'idxCustomerIP',
                'idxCustomerIPPrefix',
                'destNoCustPrefix',
                'destNoPrefix',
                'destNoCust',
                'destNoPrefixName',
                'idxSupplier',
                'idxSupplierIP',
                'idxSupplierIPPrefix',
                'destNoSuppPrefix',
                'destNoSupplier',
                'destNoSupplierPrefix',
                'destNoSupplierPrefixName',
                'destNoRealPrefix',
                'destNoRealPrefixName',
                'custTime',
                'custPrice',
                'supplierPrice',
                'supplierTime',
                'destIPOnly',
                'sourceIPOnly',
                'created',
                'modified',
                'idxServer',
                'reasonCode')
                    ->from('repaircdr')
                    ->where($condition)
                    ->orderBy('idx', 'asc') 
                    ->limit($limit);
            }
        );
        
        

        if ($inserted) {
            // Jika berhasil, hapus data dari table a berdasarkan kondisi dan limit yang sama
            $deleted = DB::table('repaircdr')->where($condition)->orderBy('idx', 'asc')->limit($limit)->delete();
            
            if ($deleted) {
                $message = array(true, 'Process Successful', 'Data updated successfully.', 'nextload(\'Yes\')');
                // $message = "Data berhasil dipindahkan dan dihapus dari table_a berdasarkan kondisi, limit, dan kolom dengan nilai null";
            } else {
                $message = array(false, 'Process Fails', 'The data could not be updated.', '');
                // $message = "Gagal menghapus data dari table_a berdasarkan kondisi, limit, dan kolom dengan nilai null setelah memindahkan ke table_b";
            }
        } else {
            $message = array(true, 'Process Successful', 'All Data updated successfully.', 'nextload(\'no\')');
            // $message = "Gagal memindahkan data ke table_b berdasarkan kondisi, limit, dan kolom dengan nilai null";
        }
        echo json_encode($message);
    }
}
