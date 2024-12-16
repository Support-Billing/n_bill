<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\dashboard\CustomerActiveController;
use App\Http\Controllers\Auth\CsrfCheckController;

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\WorklocationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SyssettingController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\BusinessentityController;
// use App\Http\Controllers\InvoicePriorityController;
// use App\Http\Controllers\StatusProjectController;
// use App\Http\Controllers\OperatorSelulerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProjectController;
// use App\Http\Controllers\NumberController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\PrefixController;
use App\Http\Controllers\PrefixgroupController;
use App\Http\Controllers\ExtensionController;
// use App\Http\Controllers\BrandController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PrefixSupplierController;
use App\Http\Controllers\CustomerGroupController;
use App\Http\Controllers\CustomerGroupPriceController;
// use App\Http\Controllers\ServersitesController;
// use App\Http\Controllers\PrefixsitesController;
// use App\Http\Controllers\SxtensionsitesController;

use App\Http\Controllers\report\ReportCdrController;
use App\Http\Controllers\report\ReportCompareDurationController;
use App\Http\Controllers\report\ReportBiayaProjectController;
use App\Http\Controllers\report\ReportBiayaCustomerController;
use App\Http\Controllers\report\ReportInvoiceController;
use App\Http\Controllers\report\ReportUsageSupplierController;
use App\Http\Controllers\report\ReportUsageCustomerController;
use App\Http\Controllers\report\ReportnewipController;

use App\Http\Controllers\feature\CalculateController;
use App\Http\Controllers\feature\RecalculateController;
use App\Http\Controllers\feature\GeneratorController;

use App\Http\Controllers\Exreport\ExreportcdrController;
use App\Http\Controllers\Exreport\ExreportbiayacustomerController;
use App\Http\Controllers\Exreport\ExreportinvoiceController;
use App\Http\Controllers\Exreport\ExreportusagesupplierController;
use App\Http\Controllers\Exreport\ExreportusagecustomerController;
use App\Http\Controllers\Exreport\ExreportnewipController;
use App\Http\Controllers\Exreport\ExreportbiayaprojectController;
// use App\Http\Controllers\Exreport\reportbiayaproject;

use App\Http\Controllers\monitoring\ElapsedTimeController;
use App\Http\Controllers\monitoring\CdrController;
use App\Http\Controllers\monitoring\CustomerCdrController;
use App\Http\Controllers\monitoring\FolderServerParserController;
use App\Http\Controllers\monitoring\FolderServerController;
use App\Http\Controllers\monitoring\FolderFileServerController;
use App\Http\Controllers\monitoring\GapFileCdrController;
use App\Http\Controllers\monitoring\GapFolderController;
use App\Http\Controllers\monitoring\FolderFileController;
use App\Http\Controllers\monitoring\RepairCdrController;
use App\Http\Controllers\monitoring\RepairCsvParserController;
use App\Http\Controllers\monitoring\SuccessCdrController;
use App\Http\Controllers\monitoring\CsvParserController;
use Illuminate\Support\Facades\Auth;

// parser 
use App\Http\Controllers\monitoring\ParserController;

use App\Http\Controllers\FilelogController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\LogController;
/*
|--------------------------------------------------------------------------
| Web Routes || {parameter1}/{parameter2}
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('repairmaster', 'App\Http\Controllers\monitoring\RepairMasterController@index');

// Auth::routes();
Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
  ]);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Route::post('csrf-check', 'App\Http\Controllers\Auth\CsrfCheckController@handleAjaxRequest')->name('csrf-check');
Route::get('customeractive', 'App\Http\Controllers\dashboard\CustomerActiveController@index');
Route::post('/customeractiveload', 'App\Http\Controllers\dashboard\CustomerActiveController@load')->name('customeractiveload');
Route::get('lastcustomer', 'App\Http\Controllers\dashboard\LastCustomerController@index');
Route::post('/lastcustomerload', 'App\Http\Controllers\dashboard\LastCustomerController@load')->name('lastcustomerload');

Route::get('customernonactive', 'App\Http\Controllers\dashboard\CustomerNonActiveController@index');
Route::post('/customernonactiveload', 'App\Http\Controllers\dashboard\CustomerNonActiveController@load')->name('customernonactiveload');

Route::get('prioritycustomer', 'App\Http\Controllers\dashboard\PriorityCustomerController@index');
Route::post('/prioritycustomerload', 'App\Http\Controllers\dashboard\PriorityCustomerController@load')->name('prioritycustomerload');

Route::get('dashboard', 'App\Http\Controllers\HomeController@dashboard');
Route::get('dashboard/{download}/download', 'App\Http\Controllers\HomeController@download')->name('download_dashboard');

Route::post('/worklocationload', 'App\Http\Controllers\WorklocationController@load')->name('worklocationload');
Route::resource('worklocation', WorklocationController::class);

Route::post('/employeeload', 'App\Http\Controllers\EmployeeController@load')->name('employeeload');
Route::resource('employee', EmployeeController::class);

Route::post('/userload', 'App\Http\Controllers\UserController@load')->name('userload');
Route::get('/profile', 'App\Http\Controllers\UserController@profile')->name('profile');
Route::resource('user', UserController::class);

Route::post('/menuload', 'App\Http\Controllers\MenuController@load')->name('menuload');
Route::PUT('/menu/proses_ordering/{data}', 'App\Http\Controllers\MenuController@proses_ordering');
Route::get('/menu/{menu}/edit_menu', 'App\Http\Controllers\MenuController@edit_menu');
Route::get('/menu/{menu}/add_child', 'App\Http\Controllers\MenuController@add_child');
Route::resource('menu', MenuController::class);

Route::post('/syssettingload', 'App\Http\Controllers\SyssettingController@load')->name('syssettingload');
Route::resource('syssetting', SyssettingController::class);

Route::post('/roleload', 'App\Http\Controllers\RoleController@load')->name('roleload');
Route::resource('role', RoleController::class);

Route::get('/sistem', 'App\Http\Controllers\LogController@index')->name('sistem');
Route::get('/activity', 'App\Http\Controllers\LogController@index')->name('activity');

Route::post('/serverload', 'App\Http\Controllers\ServerController@load')->name('serverload');
Route::get('/server/{server}/change_status', 'App\Http\Controllers\ServerController@change_status');
Route::resource('server', ServerController::class);

Route::post('/bankload', 'App\Http\Controllers\BankController@load')->name('bankload');
Route::get('/bank/{bank}/change_status', 'App\Http\Controllers\BankController@change_status');
Route::resource('bank', BankController::class);

Route::post('/businessentityload', 'App\Http\Controllers\BusinessentityController@load')->name('businessentityload');
Route::resource('businessentity', BusinessentityController::class);

Route::post('/customergroupload', 'App\Http\Controllers\CustomerGroupController@load')->name('customergroupload');
Route::get('/customergroup/{customergroup}/change_status', 'App\Http\Controllers\CustomerGroupController@change_status');
Route::get('/customergroup/{customergroup}/show_all', 'App\Http\Controllers\CustomerGroupController@show_all');
Route::resource('customergroup', CustomerGroupController::class);

Route::post('/customergrouppriceload', 'App\Http\Controllers\CustomerGroupPriceController@load')->name('customergrouppriceload');
Route::get('/customergroupprice/{customergroup}/customer_group_prices', 'App\Http\Controllers\CustomerGroupPriceController@customer_group_prices'); // ini
Route::post('/customergroupprice/{customergroup}/store_prices', 'App\Http\Controllers\CustomerGroupPriceController@store_prices')->name('customergroup.store_prices'); // dan ini
Route::get('/customergroupprice/{parameter1}/{parameter2}/change_status', 'App\Http\Controllers\CustomerGroupPriceController@change_status');
Route::DELETE('/customergroupprice/{customergroupprice}', 'App\Http\Controllers\CustomerGroupPriceController@destroy');

Route::post('/customergroupmemberload', 'App\Http\Controllers\CustomerGroupMemberController@load')->name('customergroupmemberload');
Route::get('/customergroupmember/{customergroupmember}/customer_group_members', 'App\Http\Controllers\CustomerGroupMemberController@customer_group_members'); // ini 
Route::post('/customergroupmember/{customergroupmember}/store_members', 'App\Http\Controllers\CustomerGroupMemberController@store_members')->name('customergroup.store_members'); // dan ini
Route::post('/customergroupmember/{idxCustomerGroup}/{idxCustomer}/store_project', 'App\Http\Controllers\CustomerGroupMemberController@store_project')->name('customergroup.store_project'); // dan ini
Route::get('/customergroupmember/{idxCustomerGroup}/{idxCustomer}/customer_group_project', 'App\Http\Controllers\CustomerGroupMemberController@customer_group_project'); // dan ini
Route::get('/customergroupmember/{parameter1}/{parameter2}/customer_group_list_project', 'App\Http\Controllers\CustomerGroupMemberController@customer_group_list_project'); // dan ini
Route::post('/customergroupmember/{customergroupmember}/get_selection_project', 'App\Http\Controllers\CustomerGroupMemberController@get_selection_project')->name('customergroupmember.member_get_project');

Route::post('/customerprojectload', 'App\Http\Controllers\CustomerController@load_project')->name('customerprojectload');
Route::post('/customerload', 'App\Http\Controllers\CustomerController@load')->name('customerload');
Route::get('/customer/{customer}/customer_project', 'App\Http\Controllers\CustomerController@customer_project');
Route::post('/customer/{customer}/store_project', 'App\Http\Controllers\CustomerController@store_project')->name('customer.store_project');
Route::get('/customer/{customer}/change_status', 'App\Http\Controllers\CustomerController@change_status');
Route::get('/customer/{customer}/show_all', 'App\Http\Controllers\CustomerController@show_all');
Route::get('/customer/{satu}/{dua}/destroy_project', 'App\Http\Controllers\CustomerController@destroy_project');
Route::resource('customer', CustomerController::class);

Route::post('/projectload', 'App\Http\Controllers\ProjectController@load')->name('projectload');
Route::get('/project/{project}/change_status', 'App\Http\Controllers\ProjectController@change_status');
Route::get('/project/{project}/show_all', 'App\Http\Controllers\ProjectController@show_all');
Route::get('/project/show_all_table', 'App\Http\Controllers\ProjectController@show_all_table')->name('project.show_all_table');
Route::get('/project/{project}/project_prefix', 'App\Http\Controllers\ProjectController@project_prefix');
Route::get('/project/{project}/project_accounts', 'App\Http\Controllers\ProjectController@project_accounts');
Route::get('/project/{project}/project_prefixip', 'App\Http\Controllers\ProjectController@project_prefixip');
Route::get('/project/{project}/project_prefixsvr', 'App\Http\Controllers\ProjectController@project_prefixsvr');
Route::post('/project/{project}/store_price', 'App\Http\Controllers\ProjectController@store_price')->name('project.store_price');
Route::post('/project/{project}/store_prefix', 'App\Http\Controllers\ProjectController@store_prefix')->name('project.store_prefix');
Route::post('/project/{project}/store_accounts', 'App\Http\Controllers\ProjectController@store_accounts')->name('project.store_accounts');
Route::post('/project/{project}/store_prefixip', 'App\Http\Controllers\ProjectController@store_prefixip')->name('project.store_prefixip');
Route::post('/project/{project}/store_prefixsvr', 'App\Http\Controllers\ProjectController@store_prefixsvr')->name('project.store_prefixsvr');
Route::get('/project/{satu}/{dua}/trash', 'App\Http\Controllers\ProjectController@trash');
Route::resource('project', ProjectController::class);

Route::post('/projectpriceload', 'App\Http\Controllers\ProjectPriceController@load')->name('projectpriceload');
Route::get('/project/{project}/project_price', 'App\Http\Controllers\ProjectController@project_price');
Route::post('/prefixload', 'App\Http\Controllers\PrefixController@load')->name('prefixload');
Route::resource('prefix', PrefixController::class);

Route::post('/prefixgroupload', 'App\Http\Controllers\PrefixgroupController@load')->name('prefixgroupload');
Route::get('/prefixgroup/{prefixgroup}/change_status', 'App\Http\Controllers\PrefixgroupController@change_status');
Route::resource('prefixgroup', PrefixgroupController::class);

Route::post('/extensionload', 'App\Http\Controllers\ExtensionController@load')->name('extensionload');
Route::resource('extension', ExtensionController::class);

Route::post('/departmentload', 'App\Http\Controllers\DepartmentController@load')->name('departmentload');
Route::resource('department', DepartmentController::class);

Route::post('/supplierload', 'App\Http\Controllers\SupplierController@load')->name('supplierload');
Route::get('/supplier/{supplier}/change_status', 'App\Http\Controllers\SupplierController@change_status');
Route::get('/supplier/{supplier}/show_all', 'App\Http\Controllers\SupplierController@show_all');
Route::get('/supplier/{supplier}/edit_cdr', 'App\Http\Controllers\SupplierController@edit_cdr');
Route::put('/supplier/{supplier}/update_cdr', 'App\Http\Controllers\SupplierController@update_cdr');
Route::resource('supplier', SupplierController::class);

Route::post('/prefixsupplierload', 'App\Http\Controllers\PrefixSupplierController@load')->name('prefixsupplierload');
Route::get('/prefixsupplier/{prefixsupplier}/change_status', 'App\Http\Controllers\PrefixSupplierController@change_status');
Route::get('/prefixsupplier/{prefixsupplier}/show_all', 'App\Http\Controllers\PrefixSupplierController@show_all');
Route::resource('prefixsupplier', PrefixSupplierController::class);

// Feature
Route::post('/calculate/{calculate}/update_cdr', 'App\Http\Controllers\feature\CalculateController@update_cdr')->name('calculate.update_cdr');
Route::get('/calculate/{calculate}/edit_cdr', 'App\Http\Controllers\feature\CalculateController@edit_cdr');
Route::post('/recalculate/{recalculate}/update_cdr', 'App\Http\Controllers\feature\RecalculateController@update_cdr')->name('recalculate.update_cdr');
Route::get('/recalculate/{recalculate}/edit_cdr', 'App\Http\Controllers\feature\RecalculateController@edit_cdr');
Route::get('/generator/{generator}/generator_folder', 'App\Http\Controllers\feature\GeneratorController@generator_folder');
Route::post('/generator/{generator}/generator_folder', 'App\Http\Controllers\feature\GeneratorController@run_generator_folder')->name('reportcdr.run_generator_folder');


// REPORT
Route::post('/reportcdr/{reportcdr}/get_prefix', 'App\Http\Controllers\report\ReportCdrController@get_prefix')->name('reportcdr.report_get_prefix');

Route::get('/reportcdr/{download}/download', 'App\Http\Controllers\report\ReportCdrController@download')->name('download_reportcdr');
Route::get('/reportcdr/{download}/downloadDetil', 'App\Http\Controllers\report\ReportCdrController@download_detil')->name('download_reportcdrdetil');
Route::post('/reportcdr/{encriptUrl}/encriptUrl', 'App\Http\Controllers\report\ReportCdrController@encript_url')->name('encriptUrl');
Route::post('/reportcdrload', 'App\Http\Controllers\report\ReportCdrController@load')->name('reportcdrload');
Route::post('/reportcdrloaddetil', 'App\Http\Controllers\report\ReportCdrController@load_detil')->name('reportcdrloaddetil');
Route::get('/reportcdr/{reportcdr}/show_all', 'App\Http\Controllers\report\ReportCdrController@show_all');
Route::resource('reportcdr', ReportcdrController::class);

Route::post('/reportinvoiceload', 'App\Http\Controllers\report\ReportInvoiceController@load')->name('reportinvoiceload');
Route::get('/reportinvoice/{reportinvoice}/show_all', 'App\Http\Controllers\report\ReportInvoiceController@show_all');
Route::get('/reportinvoice/{download}/downloadDetil', 'App\Http\Controllers\report\ReportInvoiceController@download_detil')->name('download_reportinvoicedetil');
Route::get('/reportinvoice/{download}/download', 'App\Http\Controllers\report\ReportInvoiceController@download')->name('download_reportinvoice');
Route::resource('reportinvoice', ReportInvoiceController::class);

Route::get('/reportbiayacustomer/{download}/download_reportbiayacustomer', 'App\Http\Controllers\report\ReportBiayaCustomerController@download')->name('download_reportbiayacustomer');
Route::post('/reportbiayacustomerload', 'App\Http\Controllers\report\ReportBiayaCustomerController@load')->name('reportbiayacustomerload');
Route::get('/reportbiayacustomer/{reportbiayacustomer}/show_all', 'App\Http\Controllers\report\ReportBiayaCustomerController@show_all');
Route::resource('reportbiayacustomer', ReportBiayaCustomerController::class);

Route::get('/reportbiayaproject/{download}/download_reportbiayaproject', 'App\Http\Controllers\report\ReportBiayaProjectController@download')->name('download_reportbiayaproject');
Route::post('/reportbiayaprojectload', 'App\Http\Controllers\report\ReportBiayaProjectController@load')->name('reportbiayaprojectload');
Route::get('/reportbiayaproject/{reportbiayaproject}/show_all', 'App\Http\Controllers\report\ReportBiayaProjectController@show_all');
Route::resource('reportbiayaproject', ReportBiayaProjectController::class);

Route::post('/reportusagesupplierload', 'App\Http\Controllers\report\ReportUsageSupplierController@load')->name('reportusagesupplierload');
Route::resource('reportusagesupplier', ReportUsageSupplierController::class);


Route::get('/reportcompareduration/{download}/download', 'App\Http\Controllers\report\ReportCompareDurationController@download')->name('download_reportcompareduration');
Route::post('/reportcomparedurationload', 'App\Http\Controllers\report\ReportCompareDurationController@load')->name('reportcomparedurationload');
Route::resource('reportcompareduration', ReportCompareDurationController::class);

Route::get('/reportusagecustomer/{download}/download_reportusageproject', 'App\Http\Controllers\report\ReportUsageCustomerController@download')->name('download_reportusageproject');
Route::get('/reportusagecustomer/{downloadsummary}/download_reportusageprojectsummary', 'App\Http\Controllers\report\ReportUsageCustomerController@downloadSummary')->name('download_reportusageprojectsummary');
Route::post('/reportusagecustomerload', 'App\Http\Controllers\report\ReportUsageCustomerController@load')->name('reportusagecustomerload');
Route::resource('reportusagecustomer', ReportUsageCustomerController::class);
Route::post('/reportnewipload', 'App\Http\Controllers\report\ReportnewipController@load')->name('reportnewipload');
Route::resource('reportnewip', ReportnewipController::class);

// EX REPORT
Route::resource('exreportcdr', ExreportcdrController::class);
Route::resource('exreportbiayacustomer', ExreportbiayacustomerController::class);
Route::resource('exreportbiayaproject', ExreportbiayaprojectController::class);
Route::resource('exreportinvoice', ExreportinvoiceController::class);
Route::resource('exreportusagesupplier', ExreportusagesupplierController::class);
Route::resource('exreportusagecustomer', ExreportusagecustomerController::class);
Route::resource('exreportnewip', ExreportnewipController::class);

// MONITORING
Route::post('/elapsedtimeload', 'App\Http\Controllers\monitoring\ElapsedTimeController@load')->name('elapsedtimeload');
Route::resource('elapsedtime', ElapsedTimeController::class);
Route::post('/cdrload', 'App\Http\Controllers\monitoring\CdrController@load')->name('cdrload');
Route::get('/cdr/{cdr}/show_all', 'App\Http\Controllers\monitoring\cdrController@show_all');
Route::resource('cdr', cdrController::class);
Route::post('/customercdrload', 'App\Http\Controllers\monitoring\CustomerCdrController@load')->name('customercdrload');
Route::resource('customercdr', customercdrController::class);
Route::post('/folderserverparserload', 'App\Http\Controllers\monitoring\FolderServerParserController@load')->name('folderserverparserload');
Route::resource('folderserverparser', folderserverparserController::class);
Route::post('/gapfilecdrload', 'App\Http\Controllers\monitoring\GapFileCdrController@load')->name('gapfilecdrload');
Route::resource('gapfilecdr', gapfilecdrController::class);
Route::post('/gapfolderload', 'App\Http\Controllers\monitoring\GapFolderController@load')->name('gapfolderload');
Route::resource('gapfolder', gapfolderController::class);
Route::post('/folderserverload', 'App\Http\Controllers\monitoring\FolderServerController@load')->name('folderserverload');
Route::get('/folderserver/{folderfile}/show_all', 'App\Http\Controllers\monitoring\FolderServerController@show_all');
Route::resource('folderserver', folderserverController::class);
Route::post('/folderfileserverload', 'App\Http\Controllers\monitoring\FolderFileServerController@load')->name('folderfileserverload');
Route::get('/folderfileserver/{folderfile}/show_all', 'App\Http\Controllers\monitoring\FolderFileServerController@show_all');
Route::resource('folderfileserver', folderfileserverController::class);
Route::post('/csvparserload', 'App\Http\Controllers\monitoring\CsvParserController@load')->name('csvparserload');
Route::resource('csvparser', csvparserController::class);

Route::post('/folderfile/{folderfile}/update_regex', 'App\Http\Controllers\monitoring\FolderFileController@update_regex')->name('folderfile.update_regex');
Route::PUT('/folderfile/{folderfile}/update_parser', 'App\Http\Controllers\monitoring\FolderFileController@update_parser')->name('folderfile.update_parser');
Route::post('/folderfile/{folderfile}/push_import', 'App\Http\Controllers\monitoring\FolderFileController@push_import')->name('folderfile.push_import');
Route::get('/folderfile/{folderfile}/import', 'App\Http\Controllers\monitoring\FolderFileController@import');
Route::get('/folderfile/{folderfile}/show_all', 'App\Http\Controllers\monitoring\FolderFileController@show_all');
Route::get('/folderfile/show_all_table_row', 'App\Http\Controllers\monitoring\FolderFileController@show_all_table_row')->name('folderfile.show_all_table_row');
Route::get('/folderfile/show_all_table_column', 'App\Http\Controllers\monitoring\FolderFileController@show_all_table_column')->name('folderfile.show_all_table_column');
Route::post('/folderfileload', 'App\Http\Controllers\monitoring\FolderFileController@load')->name('folderfileload');
Route::resource('folderfile', folderfileController::class);

Route::get('/repaircdr/{repaircdr}/repair_kolom', 'App\Http\Controllers\monitoring\RepairCdrController@repair_kolom')->name('repaircdr.repair_kolom');
Route::post('/repaircdr/{repaircdr}/update_cdr', 'App\Http\Controllers\monitoring\RepairCdrController@reason_kolom')->name('repaircdr.reason_kolom');
Route::get('/repaircdr/{repaircdr}/readymovecdr', 'App\Http\Controllers\monitoring\RepairCdrController@readymovecdr')->name('repaircdr.readymovecdr');
Route::post('/repaircdr/{repaircdr}/movecdr', 'App\Http\Controllers\monitoring\RepairCdrController@movecdr')->name('repaircdr.movecdr');
Route::post('/repaircdrload', 'App\Http\Controllers\monitoring\RepairCdrController@load')->name('repaircdrload');
Route::resource('repaircdr', repaircdrController::class);

Route::post('/repaircsvparserload', 'App\Http\Controllers\monitoring\RepairCsvParserController@load')->name('repaircsvparserload');
Route::post('/repaircsvparser/{repaircsvparser}/update_cdr', 'App\Http\Controllers\monitoring\RepairCsvParserController@reason_kolom')->name('repaircsvparser.reason_kolom');
Route::get('/repaircsvparser/{repaircsvparser}/get_kolom', 'App\Http\Controllers\monitoring\RepairCsvParserController@get_kolom')->name('repaircsvparser.get_kolom');

Route::post('/repaircsvparser/{repaircsvparser}/update_csv_customer', 'App\Http\Controllers\monitoring\RepairCsvParserController@update_csv_customer')->name('repaircsvparser.update_csv_customer');
Route::get('/repaircsvparser/{repaircsvparser}/get_kolom_customer', 'App\Http\Controllers\monitoring\RepairCsvParserController@get_kolom_customer')->name('repaircsvparser.get_kolom_customer');

Route::post('/repaircsvparser/{repaircsvparser}/update_csv_supplier', 'App\Http\Controllers\monitoring\RepairCsvParserController@update_csv_supplier')->name('repaircsvparser.update_csv_supplier');
Route::get('/repaircsvparser/{repaircsvparser}/get_kolom_supplier', 'App\Http\Controllers\monitoring\RepairCsvParserController@get_kolom_supplier')->name('repaircsvparser.get_kolom_supplier');

Route::get('/repaircsvparser/{repaircsvparser}/show_all', 'App\Http\Controllers\monitoring\RepairCsvParserController@show_all');
Route::resource('repaircsvparser', RepairCsvParserController::class);

Route::post('/successcdrload', 'App\Http\Controllers\monitoring\successcdrController@load')->name('successcdrload');
Route::resource('successcdr', successcdrController::class);

// LOG
Route::post('/filelogload', 'App\Http\Controllers\FilelogController@load')->name('filelogload');
Route::resource('filelog', FilelogController::class);
Route::post('/activityload', 'App\Http\Controllers\ActivityController@load')->name('activityload');
Route::resource('activity', ActivityController::class);
Route::post('/systemload', 'App\Http\Controllers\SystemController@load')->name('systemload');
Route::resource('system', SystemController::class);

Route::post('/log_load', 'App\Http\Controllers\LogController@load')->name('log_load');
Route::resource('log', LogController::class);