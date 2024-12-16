<?php

namespace App\Http\Controllers\feature;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;

use App\Models\Project;
use App\Models\Customer;
use App\Models\Cdr;
use App\Models\sum\ReportCdr as SumReportCdr;

use App\Services\MyService;
use App\Exports\reportcdrdetil;
use App\Exports\testReportcdrdetil;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


use DB;

class GeneratorController extends Controller
{

    private $_page_title = 'Generator CDR';
    private $_url_data = 'reportcdr';
    private $_id_role = '';
    private $_access_menu;
    private $_DataCustomer;
    private $_DataProject;
    private $_myService;
    private $_DataFolderFiles;
 
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_myService = app(MyService::class);
        $this->middleware('auth');
        $this->getFolderFile();
        $this->getProject();
        $this->getCustomer();
        $this->middleware(function ($request, $next) {
            $this->_access_menu = $this->_myService->parser_access_menu($this->_url_data);
            return $next($request);
        });
    }

    public function getProject() {
        $Projects = Project::get();
        $getProjects = array();
        foreach ($Projects as $key => $val) {
            $getProjects[$val->idxCore]['projectAlias'] = $val->projectAlias; 
            $getProjects[$val->idxCore]['idxCoreCust'] = $val->idxCoreCust; 
        }
        $this->_DataProject = $getProjects;
        
        return 'succsess';
    }
    
    public function getCustomer() {
        $Customers = Customer::with('projects')->get();
        $getCustomers = array();
        foreach ($Customers as $key => $val) {
            $getCustomers[$val->idxCore]['invoicePrior'] = $val->invoicePrior;
            $getCustomers[$val->idxCore]['clientName'] = $val->clientName;
            $getCustomers[$val->idxCore]['isInv'] = $val->isInv;
            $getCustomers[$val->idxCore]['isCustom'] = $val->isCustom;
            $getCustomers[$val->idxCore]['isTier'] = $val->isTier;
            $getCustomers[$val->idxCore]['marketingID'] = $val->marketingID;
            $getCustomers[$val->idxCore]['jmlProject'] = count($val['projects']);
            
        }
        $this->_DataCustomer = $getCustomers;
        
        return 'succsess';
    }

    public function getFolderFile() {
        $folderFiles = DB::connection('mysql_third')
            ->table('check_folder_file')
            ->select('serverData', 'folderName')
            ->groupBy('serverData', 'folderName')
            ->get();
        $getFolderFiles = array();
        foreach ($folderFiles as $key => $val) {

            switch ($val->serverData) {
                case 'ASTERISK':
                    $getFolderFiles['ASTERISK'][] = $val->folderName;
                    break;
                case 'ELASTIX':
                    $getFolderFiles['ELASTIX'][] = $val->folderName;
                    break;
                case 'MERA':
                    $getFolderFiles['MERA'][] = $val->folderName;
                    break;
                case 'VOS':
                    $getFolderFiles['VOS'][] = $val->folderName;
                    break;
            }
            
        }
        $this->_DataFolderFiles = $getFolderFiles;
        
        return 'succsess';
    }

    function clean($string) {
        $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

    public function generator_folder(string $urlData)
    {
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            
            $idxCoreProjects = array();
            $idxCorePrefixs = array();
            $folderNames = array();
            $formattedDate = '';
            $formattedDateEnd = '';
            
            if($urlData != 'all'){
                $decryptString = Crypt::decryptString($urlData);
                $pieces = explode("~", $decryptString);
            }
            
            if (isset($pieces[0])) {
                $formattedDate = $pieces[0];
            }
            
            if (isset($pieces[1])) {
                $formattedDateEnd = $pieces[1];
            }
            
            if (!empty($pieces[6])) {
                $temp_idxCoreProjects = explode(",", $pieces[6]);
                foreach ($temp_idxCoreProjects as $key => $value) {
                    $idxCoreProjects[] = $value;
                }
            }

            if (!empty($pieces[7])) {
                $temp_idxCorePrefixs = explode(",", $pieces[7]);
                foreach ($temp_idxCorePrefixs as $key => $value) {
                    $idxCorePrefixs[] = $value;
                }
            }

            if (!empty($pieces[8])) {
                $temp_folderNames = explode(",", $pieces[8]);
                foreach ($temp_folderNames as $value) {
                    $folderNames[] = $value;
                }
            }
            
            $projects = SumReportCdr::selectRaw("idxCoreProject")
                ->where(function($query) use ($formattedDate, $formattedDateEnd) {
                    if (!empty($formattedDate)) {
                        if (!empty($formattedDateEnd)) {
                            $query->whereRaw("DATE_FORMAT(date, '%Y-%m-%d') Between '{$formattedDate}' and '{$formattedDateEnd}'");
                        } else {
                            $query->whereRaw("DATE_FORMAT(date, '%Y-%m-%d') = '{$formattedDate}'");
                        }
                    }
                })
                ->when(!empty($idxCoreProjects), function ($query) use ($idxCoreProjects) {
                    return $query->whereIn('idxCoreProject', $idxCoreProjects);
                })
                ->when(!empty($idxCorePrefixs), function ($query) use ($idxCorePrefixs) {
                    return $query->whereIn('idxCorePrefix', $idxCorePrefixs);
                })
                ->when(!empty($folderNames), function ($query) use ($folderNames) {
                    return $query->whereIn('folderName', $folderNames);
                })
                ->groupBy('idxCoreProject')
                ->get();
                
            return view('feature.generator.generator_folder', [
                'page_title' => $this->_page_title,
                'projects' => $projects,
                'urlData' => $urlData
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }
    
    public function generator_folder_old(string $urlData)
    {
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {
            // $projects = Project::all();
            // $projects = Project::skip(0)->take(100)->get();
            
            // echo $urlData ;exit;
            $idxCoreProjects = array();
            if($urlData != "all"){
                $decryptString = Crypt::decryptString($urlData);
                $pieces = explode("~", $decryptString);
                if (!empty($pieces[6])) {
                    $temp_idxCoreProjects = explode(",", $pieces[6]);
                
                    foreach ($temp_idxCoreProjects as $key => $value) {
                        $idxCoreProjects[] = $value;
                    }
                }
            }
            // print_r ($pieces);exit;
            $projects = Project::with('ProjectPrefixSrvs')
            ->when(!empty($idxCoreProjects), function ($query) use ($idxCoreProjects) {
                return $query->whereIn('idxCore', $idxCoreProjects);
            })->get();

            $FolderFiles = $this-> _DataFolderFiles;
            
            return view('feature.generator.generator_folder', [
                'page_title' => $this->_page_title,
                'projects' => $projects,
                'urlData' => $urlData,
                'FolderFiles' => $FolderFiles
            ]);
        }else{
            return redirect('./#dashboard');
        }
    }

    private function createDirectory($directoryDetil) 
    {
        if (!is_dir($directoryDetil)) {
            if (mkdir($directoryDetil, 0755, true)) {
                return true;
            } else {
                // Log the error or handle it appropriately
                error_log("Failed to create directory: " . $directoryDetil);
                return false;
            }
        }
        return true; // Directory already exists
    }

    private function generateDirectoryName($idxCoreCust)
    {
        
        $isCompare =  $this->_DataCustomer[$idxCoreCust]['isInv'] == 1 ? "_CP" : "";
        $isCustom =  $this->_DataCustomer[$idxCoreCust]['isCustom'] == 1 ? "_CT" : "";
        $isTier =  $this->_DataCustomer[$idxCoreCust]['isTier'] == 1 ? "_TR" : "";
        
        $custName = $this->clean($this->_DataCustomer[$idxCoreCust]['clientName']);
        $isSales = $this->clean($this->_DataCustomer[$idxCoreCust]['marketingID']);
        $custName = $this->clean($this->_DataCustomer[$idxCoreCust]['clientName']);
        $totProj = $this->clean($this->_DataCustomer[$idxCoreCust]['jmlProject']);
        
        return $custName . '_P' . $totProj . '_' . $isSales . $isCompare . $isCustom . $isTier;
    }

    public function run_generator_folder_append_berhasil(Request $request, string $idx)
    {
        $directoryDetil = '/var/www/manage_data/data_generate/cdr_test_test20240603094913.xlsx';
        

        $results = Cdr::selectRaw("
            DATE_FORMAT(datetime, '%d/%m/%Y') AS tanggal,
            TIME_FORMAT(datetime, '%H:%i:%s') AS jam,
            CASE WHEN sourceNo = 'NODID' THEN sourceNoOut ELSE sourceNo END AS sourceNoOut,
            sourceIPOnly AS IP,
            destNo,
            elapsedTime AS WaktuReal,
            custTime AS Duration,
            custPrice AS custprice,
            (custTime/60) * custPrice AS TotalPrice
        ")
        ->where('reasonCode', 3)
        ->orderBy('datetime', 'ASC')
        ->offset(0)
        ->limit(3)
        ->get();
    
        // Lokasi file Excel
        $directoryPath = '/var/www/manage_data/data_generate/cdr_test_test20240603094913.xlsx';
        
        // Memuat file Excel
        $spreadsheet = IOFactory::load($directoryPath);
        
        // Mendapatkan worksheet aktif
        $sheet = $spreadsheet->getActiveSheet();
        
        // Menambahkan data baru ke dalam worksheet
        $startRow = 6; // Baris pertama sudah dihapus
        foreach ($results as $result) {
            $sheet->setCellValueByColumnAndRow(1, $startRow, $result->tanggal);
            $sheet->setCellValueByColumnAndRow(2, $startRow, $result->jam);
            $sheet->setCellValueByColumnAndRow(3, $startRow, $result->sourceNoOut);
            $sheet->setCellValueByColumnAndRow(4, $startRow, $result->IP);
            $sheet->setCellValueByColumnAndRow(5, $startRow, $result->destNo);
            $sheet->setCellValueByColumnAndRow(6, $startRow, $result->WaktuReal);
            $sheet->setCellValueByColumnAndRow(7, $startRow, $result->Duration);
            $sheet->setCellValueByColumnAndRow(8, $startRow, $result->custprice);
            $sheet->setCellValueByColumnAndRow(9, $startRow, $result->TotalPrice);
            $startRow++;
        }
        
        // Menyimpan perubahan ke dalam file Excel
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($directoryPath);
        
        echo 'berhasil';
    }

    public function run_generator_folder(Request $request, string $urlData)
    {
        
        // ini_set('max_execution_time', 600);
        ini_set('memory_limit', '5G'); // Setel batas memori menjadi 2 GB

        # Condition
        $cond = array();
        $idxCorePrefixs = array();
        $formattedDate = '';
        $formattedDateEnd = '';

        $directory = '/var/www/manage_data/data_generate/';
        $idxCoreProjects = $request->get('idxCoreProject');
        $startLoop = $request->get('startLoop');
        // echo '==========================';
        // print_r($idxCoreProjects);exit;
        

        if($urlData != 'all'){
            $decryptString = Crypt::decryptString($urlData);
            $pieces = explode("~", $decryptString);
        }
        
        if (isset($pieces[0])) {
            $formattedDate = $pieces[0];
        }
        
        if (isset($pieces[1])) {
            $formattedDateEnd = $pieces[1];
        }

        if (!empty($pieces[7])) {
            $temp_idxCorePrefixs = explode(",", $pieces[7]);
            foreach ($temp_idxCorePrefixs as $key => $value) {
                $idxCorePrefixs[] = $value;
            }
        }

        if (!empty($pieces[8])) {
            $temp_folderNames = explode(",", $pieces[8]);
            foreach ($temp_folderNames as $value) {
                $folderNames[] = $value;
            }
        }
        
        // if(!empty($idxCoreProjects[$startLoop])){
        if (array_key_exists($startLoop, $idxCoreProjects)) {
            $idxCoreProject = $idxCoreProjects[$startLoop];
            $startLoop = $startLoop+1;
            if($this->run_generator_byproject($directory, $idxCoreProject, $formattedDate, $formattedDateEnd, $idxCorePrefixs, $folderNames)){
                
                $DataProject = $this->_DataProject[$idxCoreProject];
                $idxCoreCust = $DataProject['idxCoreCust'];
                
                $clientName = $this->_DataCustomer[$idxCoreCust]['clientName'];
                $invoicePrior = $this->_DataCustomer[$idxCoreCust]['invoicePrior'];
                $foldName = ($invoicePrior == 1) ? "priority" : (($invoicePrior == 2) ? "secondary" : "general");
                $directoryDetil = $directory . $foldName . '/' . $this->generateDirectoryName($idxCoreCust);
                
                $message = array(true, 'Process Successful', 'Data has been generated successfully.'.$directoryDetil, "nextload('Yes', $startLoop)");
            }else{
                $message = array(true, 'Process Successful', 'Data has been generated successfully.', "nextload('no', $startLoop)");
            }
        }else{
            $message = array(true, 'Process Successful', 'Data has been generated successfully.', "nextload('no', $startLoop)");
        }
        
        echo json_encode($message);
        
    }
    
    public function run_generator_byproject($directory, $idxCoreProject, $formattedDate, $formattedDateEnd, $idxCorePrefixs, $folderNames)
    {

        $DataProject = $this->_DataProject[$idxCoreProject];
        $idxCoreCust = $DataProject['idxCoreCust'];
        $projectAlias = $DataProject['projectAlias'];
        
        $clientName = $this->_DataCustomer[$idxCoreCust]['clientName'];
        $invoicePrior = $this->_DataCustomer[$idxCoreCust]['invoicePrior'];
        $foldName = ($invoicePrior == 1) ? "priority" : (($invoicePrior == 2) ? "secondary" : "general");
        $directoryDetil = $directory . $foldName . '/' . $this->generateDirectoryName($idxCoreCust);
        
        $priceGroup = '';
        $query_get_idxCoreCustGroup = "SELECT idxCoreCustGroup FROM `dev_billsystem`.`projects` WHERE `idxCore` = '$idxCoreProject';";
        $idxCoreCustGroupResult = DB::select($query_get_idxCoreCustGroup);
        if (!empty($idxCoreCustGroupResult) && !empty($idxCoreCustGroupResult[0]->idxCoreCustGroup)) {
            $idxCoreCustGroup = $idxCoreCustGroupResult[0]->idxCoreCustGroup;
            
            $results = Cdr::selectRaw("idxCoreCustGroup, ROUND(SUM(custTime) / 60, 2) as totalCustTime ")
                ->where(function($query) use ($formattedDate, $formattedDateEnd) {
                    if (!empty($formattedDate)) {
                        if (!empty($formattedDateEnd)) {
                            $query->whereRaw("DATE_FORMAT(datetime, '%Y-%m-%d') Between '{$formattedDate}' and '{$formattedDateEnd}'");
                        } else {
                            $query->whereRaw("DATE_FORMAT(datetime, '%Y-%m-%d') = '{$formattedDate}'");
                        }
                    }
                })
                ->where('idxCoreCustGroup', $idxCoreCustGroup)
                ->whereNotNull('idxCoreProject')
                ->groupBy('idxCoreCustGroup')
                ->get();

            // Mengakses totalCustTime dari hasil pertama
            if ($results->isNotEmpty()) {
                $totalCustTime = $results->first()->totalCustTime;
                $idxCoreCustGroup = $results->first()->idxCoreCustGroup;
                $query_get_price = "
                    SELECT tarifPerMenit
                    FROM customer_group_prices
                    WHERE `idxCoreCustGroup` = '$idxCoreCustGroup'  AND 
                    $totalCustTime BETWEEN startRange AND endRange;
                ";
                
                $priceResult = DB::select($query_get_price);
                // Assuming you expect only one result
                if (!empty($priceResult)) {
                    $priceGroup = $priceResult[0]->tarifPerMenit;
                }
            } 
        }

        $results = Cdr::selectRaw("
                DATE_FORMAT(datetime, '%d/%m/%Y') AS tanggal,
                TIME_FORMAT(datetime, '%H:%i:%s') AS jam,
                CASE WHEN sourceNo = 'NODID' THEN sourceNoOut ELSE sourceNo END AS sourceNoOut,
                sourceIPOnly AS IP,
                destNo,
                elapsedTime AS WaktuReal,
                custTime AS Duration,
                custPrice AS custprice,
                (custTime/60) * custPrice AS TotalPrice
            ")
            ->where(function($query) use ($formattedDate, $formattedDateEnd) {
                if (!empty($formattedDate)) {
                    if (!empty($formattedDateEnd)) {
                        $query->whereRaw("DATE_FORMAT(datetime, '%Y-%m-%d') Between '{$formattedDate}' and '{$formattedDateEnd}'");
                    } else {
                        $query->whereRaw("DATE_FORMAT(datetime, '%Y-%m-%d') = '{$formattedDate}'");
                    }
                }
            })
            ->where('idxCoreProject', $idxCoreProject)
            ->orderBy('datetime', 'asc')
            ->get();
            

        if (isset($results[0]->tanggal)) {
        
            if ($this->createDirectory($directoryDetil)) {
                
                if (isset($results[0]->tanggal)) {
                    $priceGroup = 0;
                    $cleanProjectAlias = $this->clean($projectAlias);
                    $export = new reportcdrdetil($results, $projectAlias, $formattedDate, $formattedDateEnd, $priceGroup) ;
                    $fileName = 'cdr_' . $clientName . '_' . $cleanProjectAlias. now()->format('YmdHis') . '.xlsx';
                    $excelFile = Excel::download($export, $fileName);
                    file_put_contents($directoryDetil . '/' . $fileName, $excelFile->getFile()->getContent());
                }
                
            }
        }
        return true;
    }
}
