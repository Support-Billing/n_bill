<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class reportcdr implements FromCollection, WithEvents, ShouldAutoSize, WithStyles, WithHeadings
{
    use Exportable;

    protected $_data_result;
    protected $_title = '-';
    protected $_dateRange = '';
    protected $_All_jmlWaktuReal = 0;
    protected $_All_jmlWaktuTagih = 0;
    protected $_All_biayaTagih = 0;
    protected $_DataProject;
    protected $_DataProjectPrefixSrv;

    public function __construct($data_result, $projectAlias, $ftDateStart, $ftDateEnd, $_DataProject, $_DataProjectPrefixSrv)
    {
        $this->_data_result = $data_result;
        $this->_DataProject = $_DataProject;
        $this->_DataProjectPrefixSrv = $_DataProjectPrefixSrv;
        
        if (empty($projectAlias)) {
            $this->_title = 'Summary CDR per IP dan Server';
        }else{
            $this->_title = $projectAlias;
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
                
                $this->_All_jmlWaktuReal += (float)$cdr_data['totalWaktuReal'];
                $this->_All_jmlWaktuTagih += (float)$cdr_data['totalWaktuTagih'];
                $this->_All_biayaTagih += (float)$cdr_data['totalBiayaTagih'];
                
                $idxCoreProject = $cdr_data['idxCoreProject'];
                if (isset($this->_DataProject[$idxCoreProject])) {
                    $projectAlias = $this->_DataProject[$idxCoreProject];
                }else{
                    $projectAlias = "Kode ID Project : $idxCoreProject , gagal melakukan relasi";
                }

                $idxCorePrefix = $cdr_data['idxCorePrefix'];
                if (isset($this->_DataProjectPrefixSrv[$idxCorePrefix])) {
                    $prefixNumber = $this->_DataProjectPrefixSrv[$idxCorePrefix];
                }else{
                    $prefixNumber = "Kode ID id prefix : $idxCorePrefix , gagal melakukan relasi";
                }

                return [
                    'idxCorePrefix'  => $prefixNumber,
                    'projectAlias'  => $projectAlias,
                    'sourceIPOnly'  => $cdr_data['sourceIPOnly'],
                    'folderName'  => $cdr_data['folderName'],
                    'jmlWaktuReal'      => $cdr_data['totalWaktuReal'],
                    'jmlWaktuTagih'      => $cdr_data['totalWaktuTagih'],
                    'biayaTagih'      => $cdr_data['totalBiayaTagih']
                    // 'jmlWaktuReal'      => number_format($cdr_data['totalWaktuReal'], 0, '.', ','),
                    // 'jmlWaktuTagih'      => number_format($cdr_data['totalWaktuTagih'], 0, '.', ','),
                    // 'biayaTagih'      => number_format($cdr_data['totalBiayaTagih'], 0, '.', ',')
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
                // echo $lastColumn;
                // echo '->'.$highestRow;exit;

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

                $sheet->getStyle($cellRange)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    // HORIZONTAL_CENTER
                    // HORIZONTAL_LEFT
                    // HORIZONTAL_RIGHT
                    // HORIZONTAL_JUSTIFY

                // ================================ Body Data ================================ //
                $cellRange = 'E5:E'.$highestRow;
                $sheet->getStyle($cellRange)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
                    
                $cellRange = 'A5:'.$lastColumn.$highestRow;
                $sheet->getStyle($cellRange)
                    ->getFont()
                    ->setName('Tahoma') // Menetapkan fontFamily ke Arial, ganti dengan fontFamily yang diinginkan
                    ->setSize(8);

                // $sheet->getStyle('E'.$footerRow)->getNumberFormat()->setFormatCode('#,##0');
                // $sheet->getStyle('F'.$footerRow)->getNumberFormat()->setFormatCode('#,##0');
                // $sheet->getStyle('G'.$footerRow)->getNumberFormat()->setFormatCode('#,##0.00');

                    
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
                
                $cellRangeFooterMerge = 'A'.$footerRow.':B'.$footerRow;
                $sheet->mergeCells($cellRangeFooterMerge);
                $sheet->setCellValue('A'.$footerRow, $this->_title);
                $sheet->getStyle('A'.$footerRow)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                // $WaktuRealExc = number_format($this->_All_jmlWaktuReal, 0, '.', ',');
                // $WaktuTagihExc = number_format($this->_All_jmlWaktuTagih, 0, '.', ',');
                // $TotalPriceExc = number_format($this->_All_biayaTagih, 0, '.', ',');
                // // $sheet->setCellValue('E'.$footerRow, $WaktuRealExc);
                // // $sheet->setCellValue('F'.$footerRow, $WaktuTagihExc);
                // // $sheet->setCellValue('G'.$footerRow, $TotalPriceExc);
                // $WaktuRealExc = $this->_All_jmlWaktuReal;
                // $WaktuTagihExc = $this->_All_jmlWaktuTagih;
                // $TotalPriceExc = $this->_All_biayaTagih;
                // $sheet->setCellValueExplicit('E'.$footerRow, $WaktuRealExc, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                // $sheet->setCellValueExplicit('F'.$footerRow, $WaktuTagihExc, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                // $sheet->setCellValueExplicit('G'.$footerRow, $TotalPriceExc, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                
                // Masukkan nilai sebagai angka asli
                $sheet->setCellValue('E'.$footerRow, $this->_All_jmlWaktuReal);
                $sheet->setCellValue('F'.$footerRow, $this->_All_jmlWaktuTagih);
                $sheet->setCellValue('G'.$footerRow, $this->_All_biayaTagih);

                // Terapkan format angka dengan pemisah ribuan dan desimal (titik dan koma)
                $cellRangeE = 'E5:E'.$footerRow;
                $cellRangeF = 'F5:F'.$footerRow;
                $cellRangeG = 'G5:G'.$footerRow;
                
                $sheet->getStyle($cellRangeE)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle($cellRangeF)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle($cellRangeG)->getNumberFormat()->setFormatCode('#,##0');


                $cellRange = 'E5:G'.$footerRow;
                $sheet->getStyle($cellRange)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function headings(): array
    {
        return [
            'Prefix Number',
            'Project Name',
            'Source IP',
            'Server',
            'Jumlah Waktu Real',
            'Jumlah Waktu Tagih',
            'Biaya'
        ];
    }
}
