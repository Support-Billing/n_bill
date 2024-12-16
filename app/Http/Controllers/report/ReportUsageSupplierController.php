<?php

namespace App\Http\Controllers\report;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Services\MyService;
use App\Models\Supplier;
use DB;

class ReportUsageSupplierController extends Controller
{
    
    private $_page_title = 'Report Usage Supplier';
    private $_url_data = 'reportusagesupplier';
    private $_access_menu;
    private $_myService;
    private $_DataSupplier;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_myService = app(MyService::class);
        $this->getSupplier();
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->_access_menu = $this->_myService->parser_access_menu($this->_url_data);
            return $next($request);
        });
    }

    private function getSupplier()
    {
        $Suppliers = Supplier::get();
        $getDataSuppliers = array();
        foreach ($Suppliers as $key => $val) {
            $getDataSuppliers[$val->idx] = $val->nama;
        }
        $this->_DataSupplier = $getDataSuppliers;
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
            return view('report.reportusagesupplier.index', [
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

            $query ="
                SELECT 
                    DATE_FORMAT(datetime, '%m/%d/%Y') as Tanggal,
                    idxSupplier,
                    SUM(CASE WHEN serverData = 'MERA' THEN supplierTime ELSE 0 END) AS MERA,
                    SUM(CASE WHEN serverData = 'VOS' THEN supplierTime ELSE 0 END) AS VOS,
                    SUM(CASE WHEN serverData = 'Direct' THEN supplierTime ELSE 0 END) AS Direct,
                    SUM(supplierTime) AS TotalDuration
                FROM 
                    cdr 
                WHERE
                    ('05/17/2023' IS NULL OR DATE(datetime) = '2023-05-17')
                    AND ('05/18/2023' IS NULL OR DATE(datetime) <= '2023-05-18')
                    AND idxSupplier IS NOT NULL
                GROUP BY idxSupplier, Tanggal, serverData
                ORDER BY Tanggal
                LIMIT $limit OFFSET $offset;
            ";
            
            $countQuery = "
                SELECT COUNT(*) AS totalRows
                FROM (
                    SELECT 
                        idxSupplier,
                        DATE_FORMAT(datetime, '%m/%d/%Y') as Tanggal,
                        serverData
                    FROM 
                        cdr 
                    WHERE
                        ('05/17/2023' IS NULL OR DATE(datetime) = '2023-05-17')
                        AND ('05/18/2023' IS NULL OR DATE(datetime) <= '2023-05-18')
                    GROUP BY idxSupplier, Tanggal, serverData
                ) AS subquery
            ";

            // Eksekusi kueri untuk mendapatkan jumlah total baris
            $data_result = DB::select($query);
            $data_count = DB::select($countQuery)[0]->totalRows;

            $rows = array();
            $colspan = array();
            $no = $offset;
            
            foreach ($data_result as $value) {

                $no++;
                $nama = '';
                if (isset($this->_DataSupplier[$value->idxSupplier])) {
                    $nama = $this->_DataSupplier[$value->idxSupplier];
                }

                $rows[] = [
                    $no,
                    $value->Tanggal, // Tanggal
                    $value->idxSupplier. ' - ' .$nama, // Customer
                    $value->MERA, // Server MERA
                    $value->VOS, //Server VOS
                    $value->Direct, //Server Direct
                    $value->TotalDuration //TotalDuration
                ];
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