<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\files_csv;
use App\Models\project_prices;
use App\Models\setting_parser;

class YourCommandName extends Command
{
    protected $signature = 'your:command';
    protected $description = 'Your command description';

    public function handle()
    {
        // $this->calculate_cdr();
    }

    
    // private function calculate_cdr() {
    //     $data_result = files_csv::limit(1000)->get();
    //     $setting_parsers = setting_parser::with('setting_parser_regex', 'setting_parser_row')->get();
    //     $updates = [];
    
    //     if (!$data_result->isEmpty() && $data_result->count() >= 1) {
    //         $insert_CDR = array();
    //         $dataToInsertRepairCSV = array();
            
    //         foreach ($data_result as $value) {
    //             $idx = $value->idx;
    //             $valuesArray = str_getcsv($value->data);
    //             $jmlKolPHP = count($valuesArray);
    //             $setting_parser = array();
    //             $dataCallback = array();
                
    //             $dataServer = '';
    //             unset($ready_push);
    //             switch ($jmlKolPHP) {
    //                 case 44: // 1
    //                     $dataServer = 'MERA';
    //                     $setting_parser = $setting_parsers[0];
    //                     $call_data_server = $this->readMERA($valuesArray, $setting_parser);
    //                     break;
    //                 case 52: // 2
    //                     $dataServer = 'VOS';
    //                     $setting_parser = $setting_parsers[1];
    //                     $call_data_server = $this->readVOS($valuesArray, $setting_parser);
    //                     break;
    //                 case 18: // 3 & 4
    //                     $dataServer = 'Direct'; // ini data server ELASTIX dan ASTERISK
    //                     $setting_parser = $setting_parsers[3];
    //                     $call_data_server = $this->readEDirect($valuesArray, $setting_parser);
    //                     break;
    //                 default:
    //                     $dataServer = 'repair';
    //                     break;
    //             }
                
                
    //             $coredata = [
    //                 // 'idx' => $idx,
    //                 'FileName' => $value->FileName,
    //                 'ReadyPath' => $value->ReadyPath,
    //                 'ResultPath' => $value->ResultPath,
    //                 'DateTimeCSVtoDB' => $value->DateTimeCSVtoDB,
    //                 'LineNumber' => $value->LineNumber,
    //                 'serverData' => $dataServer,
    //                 'jmlKol' => $value->jmlKol,
    //                 'jmlKolPHP' => $jmlKolPHP,
    //                 'data' => $value->data,
    //             ];

    //             $ready_push = array_merge($coredata, $call_data_server);
                
    //             if ($jmlKolPHP == 44 || $jmlKolPHP == 52 || $jmlKolPHP == 18) {
    //                 $dataToInsertCDR[] = $ready_push;
    //             } else {
    //                 $dataToInsertRepairCSV[] = $ready_push;
    //             }
                
    //         }
    //         if (cdr::insert($dataToInsertCDR) && files_csv_repair_by_parser::insert($dataToInsertRepairCSV)) {
    //             $echo = 'Data berhasil disisipkan';
            
    //             if (files_csv::limit(1000)->delete()) {
    //                 $echo = 'Data berhasil disisipkan dan clear temp csv';
    //                 $this->fungsi_looping_lagi_sampai_data_habis();
    //             }
    //         } else {
    //             $echo = 'Gagal menyisipkan data';
    //         }
            
    //     } else {
    //         return 'Data Semuanya clear';
    //     }
    // }
}

