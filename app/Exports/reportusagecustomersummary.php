<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class reportusagecustomersummary implements FromCollection, WithEvents, ShouldAutoSize, WithStyles, WithHeadings
{
    use Exportable;

    protected $_data_result;
    protected $_title = '-';
    protected $_dateRange = '';
    protected $_All_jmlMERA = 0;
    protected $_All_jmlVOS = 0;
    protected $_All_direct = 0;
    protected $_All_total = 0;
    protected $_DataCustomerPrefixSrv = null;
    protected $_DataProject = null;
    

    public function __construct($data_result, $ProjectAlias, $ftDateStart, $ftDateEnd, $DataCustomerPrefixSrv, $DataProject)
    {
        $this->_data_result =  $data_result;
        $this->_DataCustomerPrefixSrv =  $DataCustomerPrefixSrv;
        $this->_DataProject =  $DataProject;
        if(isset($ProjectAlias)){
            $this->_title = $ProjectAlias;
        }
        
        if (!empty($ftDateStart)) {
            if (!empty($ftDateEnd)) {
                $this->_dateRange = $ftDateStart .' - '. $ftDateEnd;
            }else{
                $this->_dateRange = $ftDateStart;
            }
        }else{
            $this->_dateRange = 'All Days';
        }
    }

    public function collection()
    {
        // Konversi array menjadi koleksi (collection)
        $data_result = collect($this->_data_result);
        return $data_result->map(function ($cdr_data) {
            

            if($cdr_data != 'a'){
                
                $this->_All_jmlMERA += (float)$cdr_data['jmlWaktuRealMERA'];
                $this->_All_jmlVOS += (float)$cdr_data['jmlWaktuRealVOS'];
                $this->_All_direct += (float)$cdr_data['jmlWaktuRealDIRECT'];
                $this->_All_total += (float)$cdr_data['jmlServerData'];
                

                $aliasProject = '';
                if (isset($this->_DataProject[$cdr_data['idxCoreProject']])) {
                    $aliasProject = $this->_DataProject[$cdr_data['idxCoreProject']];
                }
                
                if (isset($this->_DataCustomerPrefixSrv[$cdr_data['idxCorePrefix']])) {
                    $PrefixNumber = $this->_DataCustomerPrefixSrv[$cdr_data['idxCorePrefix']];
                }
                
                return [
                    'idxCorePrefix'      => $PrefixNumber,
                    'idxCoreCustomer'          => $aliasProject,
                    // 'Sum_jmlMERA'    => number_format($cdr_data['jmlWaktuRealMERA'], 0, ',', ','),
                    // 'Sum_jmlVOS'    => number_format($cdr_data['jmlWaktuRealVOS'], 0, ',', ','),
                    // 'Sum_direct'    => number_format($cdr_data['jmlWaktuRealDIRECT'], 0, ',', ','),
                    // 'Sum_total'  => number_format($cdr_data['jmlServerData'], 0, ',', ',')
                    'Sum_jmlMERA'    => $cdr_data['jmlWaktuRealMERA'],
                    'Sum_jmlVOS'    => $cdr_data['jmlWaktuRealVOS'],
                    'Sum_direct'    => $cdr_data['jmlWaktuRealDIRECT'],
                    'Sum_total'    => $cdr_data['jmlServerData']
                ];
                
            }else{
                return [
                    'tanggal'      => ''
                ];

            }
        });
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                // Mendapatkan lembar kerja saat ini
                $sheet = $event->sheet;
                
                // Mendapatkan baris tertinggi
                $highestRow = $sheet->getHighestRow(); 
                // Mendapatkan kolom terakhir pada header
                $lastColumn = $sheet->getHighestColumn();

                // ================================ Header ================================ //
                // Menggabungkan sel untuk nama user/title
                $sheet->mergeCells('A1:' . $lastColumn . '1');
                // isi data User/title
                $sheet->setCellValue('A1', $this->_title);
                // Mengatur gaya untuk header 
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14, // Ukuran font (misalnya 14pt)
                        'color' => ['rgb' => '000080'], // Warna font 
                        'name' => 'Tahoma' // Nama keluarga font (misalnya Arial)
                    ],
                    'alignment' => [
                        'horizontal' => 'center', // Pusatkan secara horizontal
                        'vertical' => 'middle' // Pusatkan secara vertikal
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'none', // Set borderStyle menjadi none
                            'color' => ['rgb' => 'FFFFFF'], // Set warna border menjadi putih
                        ],
                    ],
                ]);

                // ================================ Date Head ================================ //
                // // Menggabungkan sel untuk rentang tanggal
                $sheet->mergeCells('A2:' . $lastColumn . '3');

                // isi data tanggal
                $sheet->setCellValue('A2', $this->_dateRange);
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => [
                        'bold' => false,
                        'size' => 8, // Ukuran font (misalnya 14pt)
                        'color' => ['rgb' => '000080'] , // Warna font 
                        'name' => 'Tahoma' // Nama keluarga font (misalnya Arial)
                    ],
                    'alignment' => [
                        'horizontal' => 'center', // Pusatkan secara horizontal
                        'vertical' => 'top' // Pusatkan secara vertikal
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'none', // Set borderStyle menjadi none
                            'color' => ['rgb' => 'FFFFFF'], // Set warna border menjadi putih
                        ],
                    ],
                ]);

                // ================================ Head Search ================================ //
                $headings = $this->headings();
                foreach ($headings as $index => $heading) {
                    $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
                    $sheet->setCellValue($column . '4', $heading);
                }
                // Mengatur style untuk header data
                $cellRange = 'A4:' . $lastColumn . '4';
                $sheet->setAutoFilter($cellRange);
                $sheet->getStyle($cellRange)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('000080'); // Ganti dengan warna yang diinginkan

                $sheet->getStyle($cellRange)
                    ->getFont()
                    ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE)) // Mengatur warna teks menjadi putih
                    ->setName('Tahoma') // Menetapkan fontFamily ke Arial, ganti dengan fontFamily yang diinginkan
                    ->setSize(8);

                $event->sheet->getStyle($cellRange)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    // HORIZONTAL_CENTER
                    // HORIZONTAL_LEFT
                    // HORIZONTAL_RIGHT
                    // HORIZONTAL_JUSTIFY

                // ================================ Body Data ================================ //
                $cellRange = 'C5:G'.$highestRow;
                $sheet->getStyle($cellRange)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
                    
                $cellRange = 'A5:'.$lastColumn.$highestRow;
                $sheet->getStyle($cellRange)
                    ->getFont()
                    ->setName('Tahoma') // Menetapkan fontFamily ke Arial, ganti dengan fontFamily yang diinginkan
                    ->setSize(8);
                
                $cellRange = 'D5:G'.$highestRow;
                $sheet->getStyle($cellRange)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    

                // ================================ Footer Data ================================ //
                // Mengatur style untuk Footer data
                $footerRow = $highestRow+2;
                $cellRangeFooter = 'A'.$footerRow.':'.$lastColumn.$footerRow;
                $sheet->getStyle($cellRangeFooter)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('000080'); // Ganti dengan warna yang diinginkan

                $sheet->getStyle($cellRangeFooter)
                    ->getFont()
                    ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE)) // Mengatur warna teks menjadi putih
                    ->setName('Tahoma') // Menetapkan fontFamily ke Arial, ganti dengan fontFamily yang diinginkan
                    ->setSize(8);
                    
                $sheet->getStyle($cellRangeFooter)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                        

                $cellRangeFooterMerge = 'A'.$footerRow.':B'.$footerRow;
                $sheet->mergeCells($cellRangeFooterMerge);
                $sheet->setCellValue('A'.$footerRow, $this->_title);
                $sheet->getStyle('A'.$footerRow)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    
                $sheet->setCellValue('C'.$footerRow, $this->_All_jmlMERA);
                $sheet->setCellValue('D'.$footerRow, $this->_All_jmlVOS);
                $sheet->setCellValue('E'.$footerRow, $this->_All_direct);
                $sheet->setCellValue('F'.$footerRow, $this->_All_total);

                
                $cellRangeC = 'C5:C'.$footerRow;
                $cellRangeD = 'D5:D'.$footerRow;
                $cellRangeE = 'E5:E'.$footerRow;
                $cellRangeF = 'F5:F'.$footerRow;
                // $cellRangeG = 'G5:G'.$footerRow;
                
                $sheet->getStyle($cellRangeC)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle($cellRangeD)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle($cellRangeE)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle($cellRangeF)->getNumberFormat()->setFormatCode('#,##0');
                // $sheet->getStyle($cellRangeG)->getNumberFormat()->setFormatCode('#,##0');

                
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Menetapkan format sel sebagai 'Number' untuk seluruh sel di spreadsheet
            1 => [
                'numberFormat' => [
                    'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER,
                ],
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Prefix',
            'Project',
            'MERA',
            'VOS',
            'Direct',
            'Total'
        ];
    }
}
