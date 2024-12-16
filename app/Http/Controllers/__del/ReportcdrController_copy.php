<?php

namespace App\Http\Controllers\report;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;

use App\Models\cdr;
use App\Models\customer;
use App\Models\files_csv;
use App\Models\setting_parser;
use App\Models\setting_parser_regex;
use App\Models\setting_parser_row;

use App\Services\MyService;
use App\Exports\CdrExport;
// use App\Exports\ProjectExport;
use App\Exports\reportcdr;

use DB;

class ReportcdrController_copy extends Controller
{

    private $_page_title = 'Report CDR';
    private $_url_data = 'reportcdr';
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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            return view('report.reportcdr.index', [
                'page_title' => $this->_page_title,
                'import_otoritas_modul' => $this->_access_menu->import_otoritas_modul
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }
    
    function clean($string) {
        $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

    private function generateDirectoryName($value) {
        $isCompare = $value->isInv == 1 ? "_CP" : "";
        $isCustom = $value->isCustom == 1 ? "_CT" : "";
        $isTier = $value->isTier == 1 ? "_TR" : "";
        
        $custName = $this->clean($value->clientName);
        $isSales = $this->clean($value->marketingID);
        $totProj = count($value['project']);

        return $custName . '_P' . $totProj . '_' . $isSales . $isCompare . $isCustom . $isTier;
    }
    
    private function createDirectory($directoryDetil) {
        if (!is_dir($directoryDetil)) {
            if (mkdir($directoryDetil, 0777, true)) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function run_generator_folder(string $idx)
    {
        $directory = 'C:\var\www\bill_system/generate_data/';
        $data_result = Customer::with('project')->get();

        foreach ($data_result as $value) {
            $clientName = $this->clean($value->clientName);
            $foldName = ($value->invoicePrior == 1) ? "priority" : (($value->invoicePrior == 2) ? "secondary" : "general");
            
            $directoryDetil = $directory . $foldName . '/' . $this->generateDirectoryName($value);
            unset($value_projects);
            if ($this->createDirectory($directoryDetil)) {
                $value_projects = $value['project'];
                
                if ($value_projects->isNotEmpty() && $value_projects->count() >= 1 ) {
                    foreach($value_projects as $key_pro => $val_pro){
                        
                        $projectID = $val_pro->projectID;
                        $queryCdr = Cdr::query();
                        $queryCdr->where('idxCustomer', $projectID);
                        $resultCdr = $queryCdr->select('idx', 'filename', 'datetime')->get();
                            
                        if ($resultCdr->count() >= 1) {
                            $export = new CdrExport($resultCdr);
                            $projectName = $this->clean($val_pro->projectName);
                            // Tentukan nama file
                            $fileName = 'cdr_' . $clientName . '_' . $projectName. now()->format('YmdHis') . '.xlsx';

                            // Download file Excel
                            $excelFile = Excel::download($export, $fileName);

                            // Simpan file Excel ke dalam folder yang ditentukan
                            file_put_contents($directoryDetil . '/' . $fileName, $excelFile->getFile()->getContent());
                    
                        }
                    }
                }
            }
        }

        // Response dengan pesan (gunakan sesuai kebutuhan)
        $message = array(true, 'Process Successful', 'Data updated successfully.', '');
        echo json_encode($message);
        // $message = ['success' => true, 'message' => 'Process Successful', 'description' => 'Data updated successfully.'];
        // return response()->json($message);
    }

    // public function run_generator_folder(string $idx)
    // {
    //     $directory = 'C:\var\www\bill_system/generate_data/';
    //     $data_result = customer::with('project')->get();

    //     foreach ($data_result as $value) {
    //         $foldName = ($value->invoicePrior == 1) ? "priority" : (($value->invoicePrior == 2) ? "secondary" : "general");
            
    //         $directoryDetil = $directory . $foldName .'/'. $this->generateDirectoryName($value);
    //         if($this->createDirectory($directoryDetil)){




    //             $export = new CdrExport();

    //             // Tentukan path penyimpanan di server
    //             $path = storage_path('app/excel_exports/cdr/'); // Sesuaikan dengan path yang diinginkan
        
    //             // Tentukan nama file
    //             $fileName = 'cdr_' . now()->format('YmdHis') . '.xlsx';
        
    //             // Simpan file Excel ke dalam folder yang ditentukan
    //             Excel::store($export, $directoryDetil . $fileName);
    
    //             $value['project'];
    //             $directoryDetil;
    //         }
    //     }

    //     // $this->calculate_cdr();
    //     $message = array(true, 'Process Successful', 'Data updated successfully.', 'proses_parser(\'asdasd\')');
    // }
    
    private function get_customer($valuesArray, $setting_parser) {
    }


    private function getRawData($valuesArray, $setting_parser, $columnName) {
        $columnIndex = intval(substr($setting_parser->$columnName, 1)) - 1;
        $clearData = '';
    
        if ($columnIndex >= 0 && $columnIndex < count($valuesArray)) {
            $columnValue = $valuesArray[$columnIndex];
            $regex_columnName = 'regex_'.$columnName;
            $regex = $setting_parser->setting_parser_regex->$regex_columnName;
    
            if (!empty($regex)) {
                $clearData = $this->_myService->regex_clear_data($columnValue, $regex);
            }else{
                $clearData = $columnValue;
            }
        }
        
        return $clearData;
    }

    private function getColumnData($valuesArray, $setting_parser, $columnName) {
        $columnIndex = intval(substr($setting_parser->$columnName, 1)) - 1;
        $clearData = '';
    
        if ($columnIndex >= 0 && $columnIndex < count($valuesArray)) {
            $columnValue = $valuesArray[$columnIndex];
            $regex_columnName = 'regex_'.$columnName;
            $regex = $setting_parser->setting_parser_regex->$regex_columnName;
    
            if (!empty($regex)) {
                $clearData = $this->_myService->regex_clear_data($columnValue, $regex);
            }else{
                $clearData = $columnValue;
            }
        }
        
        return $clearData;
    }

    private function readMERA($valuesArray, $setting_parser) {
        
        $columns = ['datetime', 'sourceNo','sourceNoOut','sourceIP','elapsedTime','destNo','destNoOut','destIP','destName', 'sourceIPOnly'];
        foreach ($columns as $column) {
            
            switch ($column) {
                case 'datetime':
                    $clear_data_datetime = $this->getColumnData($valuesArray, $setting_parser, $column);
                    $parts = explode(' ', $clear_data_datetime);
                    $time = $parts[0];  // Jam, menit, dan detik
                    $year = $parts[5];  // Tahun
                    $month = $parts[3]; // Nama bulan
                    $day = $parts[4];   // Tanggal
                    $newDate = date('Y-m-d H:i:s', strtotime("$year-$month-$day $time"));
                    $cdrdata[$column] = $newDate;
                    break;
                default:
                    $cdrdata[$column] = $this->getColumnData($valuesArray, $setting_parser, $column);
                    break;
            }
            
        }
        
        return $cdrdata;
        
    }
    
    private function readVOS($valuesArray, $setting_parser) {
        $cdrdata = array();
        // datetime
        $cdrdata['datetime'] = 'test';
        // sourceNo
        $cdrdata['sourceNo'] = 'test';
        // sourceNoOut
        $cdrdata['sourceNoOut'] = 'test';
        // sourceIP
        $cdrdata['sourceIP'] = 'test';
        // elapsedTime
        $cdrdata['elapsedTime'] = 'test';
        // destNo
        $cdrdata['destNo'] = 'test';
        // destNoOut
        $cdrdata['destNoOut'] = 'test';
        // destIP
        $cdrdata['destIP'] = 'test';
        // destName
        $cdrdata['destName'] = 'test';

        
        return $cdrdata;
    }
    
    // ASTERISK dan ASTERISK
    private function readEDirect() {
        $cdrdata = array();
        // datetime
        $cdrdata['datetime'] = 'test';
        // sourceNo
        $cdrdata['sourceNo'] = 'test';
        // sourceNoOut
        $cdrdata['sourceNoOut'] = 'test';
        // sourceIP
        $cdrdata['sourceIP'] = 'test';
        // elapsedTime
        $cdrdata['elapsedTime'] = 'test';
        // destNo
        $cdrdata['destNo'] = 'test';
        // destNoOut
        $cdrdata['destNoOut'] = 'test';
        // destIP
        $cdrdata['destIP'] = 'test';
        // destName
        $cdrdata['destName'] = 'test';

        
        return $cdrdata;
    }


    private function calculate_cdr() {
        $data_result = files_csv::limit(1000)->get();
        $setting_parsers = setting_parser::with('setting_parser_regex', 'setting_parser_row')->get();
        $updates = [];
    
        if (!$data_result->isEmpty() && $data_result->count() >= 1) {
            $insert_CDR = array();
            $dataToInsertRepairCSV = array();
            
            foreach ($data_result as $value) {
                $idx = $value->idx;
                $valuesArray = str_getcsv($value->data);
                $jmlKolPHP = count($valuesArray);
                $setting_parser = array();
                $dataCallback = array();
                
                $dataServer = '';
                unset($ready_push);
                switch ($jmlKolPHP) {
                    case 44: // 1
                        $dataServer = 'MERA';
                        $setting_parser = $setting_parsers[0];
                        $call_data_server = $this->readMERA($valuesArray, $setting_parser);
                        break;
                    case 52: // 2
                        $dataServer = 'VOS';
                        $setting_parser = $setting_parsers[1];
                        $call_data_server = $this->readVOS($valuesArray, $setting_parser);
                        break;
                    case 18: // 3 & 4
                        $dataServer = 'Direct'; // ini data server ELASTIX dan ASTERISK
                        $setting_parser = $setting_parsers[3];
                        $call_data_server = $this->readEDirect($valuesArray, $setting_parser);
                        break;
                    default:
                        $dataServer = 'repair';
                        break;
                }
                
                
                $coredata = [
                    // 'idx' => $idx,
                    'FileName' => $value->FileName,
                    'ReadyPath' => $value->ReadyPath,
                    'ResultPath' => $value->ResultPath,
                    'DateTimeCSVtoDB' => $value->DateTimeCSVtoDB,
                    'LineNumber' => $value->LineNumber,
                    'serverData' => $dataServer,
                    'jmlKol' => $value->jmlKol,
                    'jmlKolPHP' => $jmlKolPHP,
                    'data' => $value->data,
                ];

                $ready_push = array_merge($coredata, $call_data_server);
                
                if ($jmlKolPHP == 44 || $jmlKolPHP == 52 || $jmlKolPHP == 18) {
                    $dataToInsertCDR[] = $ready_push;
                } else {
                    $dataToInsertRepairCSV[] = $ready_push;
                }
                
            }
            if (cdr::insert($dataToInsertCDR) && files_csv_repair_by_parser::insert($dataToInsertRepairCSV)) {
                $echo = 'Data berhasil disisipkan';
            
                if (files_csv::limit(1000)->delete()) {
                    $echo = 'Data berhasil disisipkan dan clear temp csv';
                    $this->fungsi_looping_lagi_sampai_data_habis();
                }
            } else {
                $echo = 'Gagal menyisipkan data';
            }
            
        } else {
            return 'Data Semuanya clear';
        }
    }


    public function generator_folder(string $idx)
    {
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            $customers = customer::all();
            return view('report.reportcdr.generator_folder', [
                'page_title' => $this->_page_title,
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

            if (!empty($data_cond['keyword'])) {
                $cond[] = ['name', 'LIKE', '%' . $data_cond['keyword'] . '%'];
            }

            /*
                // $data_result = cdr::where($cond)->offset($offset)->limit($limit)->get();
                // $data_count = cdr::where($cond)->count();
                
                // $data_result = DB::table('cdr')
                // ->select(
                //     'customers.clientName',
                //     DB::raw("DATE_FORMAT(datetime, '%m/%d/%Y') AS tanggal"),
                //     DB::raw("TIME_FORMAT(datetime, '%H:%i:%s') AS jam"),
                //     DB::raw("CASE WHEN sourceNo = 'NODID' THEN sourceNoOut ELSE sourceNo END AS sourceNoOut"),
                //     'sourceIPOnly AS IP',
                //     'destno',
                //     DB::raw("CASE WHEN destNoPrefixName IS NULL THEN destNoRealPrefixName ELSE destNoPrefixName END AS destNoPrefixName"),
                //     'destIPOnly AS destIP',
                //     'elapsedtime AS WaktuReal',
                //     'custtime AS Duration',
                //     DB::raw("CASE WHEN 0 = 1 AND customer_frees.idx IS NOT NULL THEN 0 ELSE COALESCE(customer_group_prices.tarifPerMenit, Custprice) END AS custprice"),
                //     DB::raw("CAST(custtime AS DECIMAL(10, 2)) / 60 AS DurationMin"),
                //     DB::raw("CAST(custtime AS DECIMAL(10, 2)) / 60 * CASE WHEN 0 = 1 AND customer_frees.idx IS NOT NULL THEN 0 ELSE COALESCE(customer_group_prices.tarifPerMenit, Custprice) END AS TotalPrice"),
                //     DB::raw("COALESCE(prefix_groups.telkomPrice, realprefixgroup.telkomPrice) AS telkomPrice"),
                //     DB::raw("CAST(custtime AS DECIMAL(10, 2)) / 60 * COALESCE(prefix_groups.telkomPrice, realprefixgroup.telkomPrice) AS TotalTelkom"),
                //     'MinComm'
                // )
                // ->join('customers', function ($join) {
                //     $join->on('cdr.idxCustomer', '=', 'customers.idx')
                //         ->where('customers.statusData', 1)
                //         ->where(function ($query) {
                //             $query->where('customers.priority', 1)
                //                 ->orWhere('51566', '<>', -1);
                //         });
                // })
                // ->leftJoin('prefix_groups', 'destNoPrefixName', '=', 'prefix_groups.nama')
                // ->leftJoin('prefix_groups as realprefixgroup', function ($join) {
                //     $join->on('destNoRealPrefixName', '=', 'realprefixgroup.nama')
                //         ->whereNull('prefix_groups.nama');
                // })
                // ->leftJoin('customer_group_members', 'cdr.idxCustomer', '=', 'customer_group_members.idxCustomer')
                // ->leftJoin('elapsedtime_cus_gro_member', 'customer_group_members.idxCustomerGroup', '=', 'elapsedtime_cus_gro_member.customer_group')
                // ->leftJoin('customer_group_prices', function ($join) {
                //     $join->on('customer_group_prices.idxCustomerGroup', '=', 'elapsedtime_cus_gro_member.customer_group')
                //         ->whereBetween('groupTotal', ['startRange', 'endRange']);
                // })
                // ->leftJoin('customer_frees', function ($join) {
                //     $join->on('customers.idx', '=', 'customer_frees.idxCustomer')
                //         ->whereBetween('DATE(datetime)', ['startdate', 'enddate']);
                // })
                // ->where(function ($query) {
                //     $query->where('51566', '=', -1)
                //         ->orWhereNull('51566')
                //         ->orWhere('cdr.idxCustomer', '=', 51566);
                // })
                // ->where(function ($query) {
                //     $query->whereNull('05/17/2023')
                //         ->orWhereDate('datetime', '=', '2023-05-17');
                // })
                // ->where(function ($query) {
                //     $query->whereNull('05/18/2023')
                //         ->orWhereDate('datetime', '<=', '2023-05-18');
                // })
                // ->where(DB::raw('COALESCE(customer_group_members.active, 1)'), '=', 1)
                // ->orderBy('clientName')
                // ->orderBy('tanggal')
                // ->orderBy('jam')
                // ->get();
            */
        


            $query = "
                SELECT
                    customers.clientName,
                    DATE_FORMAT(datetime, '%m/%d/%Y') AS tanggal,
                    TIME_FORMAT(datetime, '%H:%i:%s') AS jam,
                    CASE WHEN sourceNo = 'NODID' THEN sourceNoOut ELSE sourceNo END AS sourceNoOut,
                    sourceIPOnly AS IP,
                    destno,
                    CASE WHEN destNoPrefixName IS NULL THEN destNoRealPrefixName ELSE destNoPrefixName END AS destNoPrefixName,
                    destIPOnly AS destIP,
                    elapsedtime AS WaktuReal,
                    custtime AS Duration,
                    CASE WHEN 0 = 1 AND customer_frees.idx IS NOT NULL THEN 0 ELSE COALESCE(customer_group_prices.tarifPerMenit, Custprice) END AS custprice,
                    CAST(custtime AS DECIMAL(10, 2)) / 60 AS DurationMin,
                    CAST(custtime AS DECIMAL(10, 2)) / 60 * CASE WHEN 0 = 1 AND customer_frees.idx IS NOT NULL THEN 0 ELSE COALESCE(customer_group_prices.tarifPerMenit, Custprice) END AS TotalPrice,
                    COALESCE(prefix_groups.telkomPrice, realprefixgroup.telkomPrice) AS telkomPrice,
                    CAST(custtime AS DECIMAL(10, 2)) / 60 * COALESCE(prefix_groups.telkomPrice, realprefixgroup.telkomPrice) AS TotalTelkom,
                    MinComm
                FROM cdr
                INNER JOIN customers ON cdr.idxCustomer = customers.idx AND customers.statusData = 1 AND (303 <> -1 OR customers.priority = 1)
                LEFT OUTER JOIN prefix_groups ON destNoPrefixName = prefix_groups.nama
                LEFT OUTER JOIN prefix_groups realprefixgroup ON destNoRealPrefixName = realprefixgroup.nama AND prefix_groups.nama IS NULL
                LEFT OUTER JOIN customer_group_members ON cdr.idxCustomer = customer_group_members.idxCustomer
                LEFT OUTER JOIN elapsedtime_cus_gro_member ON customer_group_members.idxCustomerGroup = elapsedtime_cus_gro_member.customer_group
                LEFT OUTER JOIN customer_group_prices ON customer_group_prices.idxCustomerGroup = elapsedtime_cus_gro_member.customer_group AND groupTotal BETWEEN startRange AND endRange
                LEFT OUTER JOIN customer_frees ON customers.idx = customer_frees.idxCustomer AND DATE(datetime) BETWEEN startdate AND enddate
                WHERE
                    (303 = -1 OR 303 IS NULL OR cdr.idxCustomer = 303)
                    AND ('05/17/2023' IS NULL OR DATE(datetime) = '2023-05-17')
                    AND ('05/18/2023' IS NULL OR DATE(datetime) <= '2023-05-18')
                    AND COALESCE(customer_group_members.active, 1) = 1
                ORDER BY clientName, tanggal, jam;
            ";

            $data_result = DB::select($query);
            $data_count = count($data_result);
        

            $rows = array();
            $no = $offset;
            $TotalPrice = 0;
            $WaktuReal = 0;
            $Duration = 0;
            foreach ($data_result as $value) {
                $clientName = $value->clientName;
                $WaktuReal = $WaktuReal + $value->WaktuReal;
                $Duration = $Duration + $value->Duration;
                $TotalPrice = $TotalPrice + $value->TotalPrice;
                $no++;
                $rows[] = array(
                    $no,
                    // $value->clientName,
                    $value->tanggal,
                    $value->jam,
                    $value->sourceNoOut,
                    $value->IP, 
                    $value->destno,
                    $value->WaktuReal,
                    $value->Duration,
                    $value->DurationMin,
                    $value->TotalPrice
                );
                // no asal, IP, no tujuan, WaktuReal, Duration, tarif per menit
            }
            
            
            $data = array(
                "draw" => $draw,
                "recordsTotal" => $data_count,
                "recordsFiltered" => $data_count,
                "data_head_body" => "<tr><td colspan='10' class='text-center' > $clientName </td></tr><tr><td colspan='10' class='text-center' > 05/17/2023 - 05/18/2023 </td></tr>",
                "data_footer_body" => "<tr><td colspan='6' class='text-right' > $clientName </td><td>$WaktuReal</td><td>$Duration</td><td>&nbsp;</td><td>$TotalPrice</td></tr>",
                "data" => $rows
            );

            echo json_encode($data);
        }else{
            return redirect('./#dashboard');
        }
    }
    
    public function download($dataStatus)
    {
        $query = "
            SELECT
                customers.clientName,
                DATE_FORMAT(datetime, '%m/%d/%Y') AS tanggal,
                TIME_FORMAT(datetime, '%H:%i:%s') AS jam,
                CASE WHEN sourceNo = 'NODID' THEN sourceNoOut ELSE sourceNo END AS sourceNoOut,
                sourceIPOnly AS IP,
                destno,
                CASE WHEN destNoPrefixName IS NULL THEN destNoRealPrefixName ELSE destNoPrefixName END AS destNoPrefixName,
                destIPOnly AS destIP,
                elapsedtime AS WaktuReal,
                custtime AS Duration,
                CASE WHEN 0 = 1 AND customer_frees.idx IS NOT NULL THEN 0 ELSE COALESCE(customer_group_prices.tarifPerMenit, Custprice) END AS custprice,
                CAST(custtime AS DECIMAL(10, 2)) / 60 AS DurationMin,
                CAST(custtime AS DECIMAL(10, 2)) / 60 * CASE WHEN 0 = 1 AND customer_frees.idx IS NOT NULL THEN 0 ELSE COALESCE(customer_group_prices.tarifPerMenit, Custprice) END AS TotalPrice,
                COALESCE(prefix_groups.telkomPrice, realprefixgroup.telkomPrice) AS telkomPrice,
                CAST(custtime AS DECIMAL(10, 2)) / 60 * COALESCE(prefix_groups.telkomPrice, realprefixgroup.telkomPrice) AS TotalTelkom,
                MinComm
            FROM cdr
            INNER JOIN customers ON cdr.idxCustomer = customers.idx AND customers.statusData = 1 AND (303 <> -1 OR customers.priority = 1)
            LEFT OUTER JOIN prefix_groups ON destNoPrefixName = prefix_groups.nama
            LEFT OUTER JOIN prefix_groups realprefixgroup ON destNoRealPrefixName = realprefixgroup.nama AND prefix_groups.nama IS NULL
            LEFT OUTER JOIN customer_group_members ON cdr.idxCustomer = customer_group_members.idxCustomer
            LEFT OUTER JOIN elapsedtime_cus_gro_member ON customer_group_members.idxCustomerGroup = elapsedtime_cus_gro_member.customer_group
            LEFT OUTER JOIN customer_group_prices ON customer_group_prices.idxCustomerGroup = elapsedtime_cus_gro_member.customer_group AND groupTotal BETWEEN startRange AND endRange
            LEFT OUTER JOIN customer_frees ON customers.idx = customer_frees.idxCustomer AND DATE(datetime) BETWEEN startdate AND enddate
            WHERE
                (303 = -1 OR 303 IS NULL OR cdr.idxCustomer = 303)
                AND ('05/17/2023' IS NULL OR DATE(datetime) = '2023-05-17')
                AND ('05/18/2023' IS NULL OR DATE(datetime) <= '2023-05-18')
                AND COALESCE(customer_group_members.active, 1) = 1
            ORDER BY clientName, tanggal, jam;
        ";

        $data_result = DB::select($query);
        $data_count = count($data_result);
        $namaFile = 'reportcdr';
        return Excel::download(new reportcdr($data_result), $namaFile.'.xlsx');
    }
}
