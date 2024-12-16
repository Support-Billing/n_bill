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

class ReportCdrDetil implements FromCollection, WithEvents, ShouldAutoSize, WithStyles, WithHeadings
{
    use Exportable;

    protected $_data_result;
    protected $_title = '-';
    protected $_dateRange = '';
    protected $_All_WaktuReal = 0;
    protected $_All_Duration = 0;
    protected $_All_TotalPrice = 0;

    public function __construct($data_result, $ProjectAlias, $ftDateStart, $ftDateEnd)
    {
        $this->_data_result =  $data_result;   
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
                // DATE_FORMAT(datetime, '%d/%m/%Y') AS tanggal,
                // TIME_FORMAT(datetime, '%H:%i:%s') AS jam,
                // CASE WHEN sourceNo = 'NODID' THEN sourceNoOut ELSE sourceNo END AS sourceNoOut,
                // sourceIPOnly AS IP,
                // destNo,
                // elapsedTime AS WaktuReal,
                // custTime AS Duration,
                // custPrice AS custprice,
                // (custTime/60) * custPrice AS TotalPrice

                $this->_All_WaktuReal += (float)$cdr_data['WaktuReal'];
                $this->_All_Duration += (float)$cdr_data['Duration'];
                $this->_All_TotalPrice += (float)$cdr_data['TotalPrice'];
                return [
                    'tanggal'      => $cdr_data['tanggal'],
                    'jam'          => $cdr_data['jam'],
                    'sourceNoOut'  => $cdr_data['sourceNoOut'],
                    'IP'           => $cdr_data['IP'],
                    'destNo'       => $cdr_data['destNo'],
                    'WaktuReal'    => number_format($cdr_data['WaktuReal'], 0, ',', '.'),
                    'Duration'     => number_format($cdr_data['Duration'], 0, ',', '.'),
                    'DurationMin'  => $cdr_data['custprice'],
                    'TotalPrice'  => number_format($cdr_data['TotalPrice'], 2, ',', '.')
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
                $cellRange = 'E5:E'.$highestRow;
                $sheet->getStyle($cellRange)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
                    
                $cellRange = 'A5:'.$lastColumn.$highestRow;
                $sheet->getStyle($cellRange)
                    ->getFont()
                    ->setName('Tahoma') // Menetapkan fontFamily ke Arial, ganti dengan fontFamily yang diinginkan
                    ->setSize(8);
                
                $cellRange = 'I5:I'.$highestRow;
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
                        

                $cellRangeFooterMerge = 'A'.$footerRow.':E'.$footerRow;
                $sheet->mergeCells($cellRangeFooterMerge);
                $sheet->setCellValue('A'.$footerRow, $this->_title);
                $sheet->getStyle('A'.$footerRow)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                $WaktuRealExc = number_format($this->_All_WaktuReal, 0, ',', '.');
                $DurationExc = number_format($this->_All_Duration, 0, ',', '.');
                $TotalPriceExc = number_format($this->_All_TotalPrice, 2, ',', '.');
                
                $sheet->setCellValue('F'.$footerRow, $WaktuRealExc);
                $sheet->setCellValue('G'.$footerRow, $DurationExc);
                $sheet->setCellValue('I'.$footerRow, $TotalPriceExc);
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
            'Tanggal',
            'Jam',
            'No. Asal',
            'IP',
            'No. Tujuan',
            'Waktu Real',
            'Waktu Tagih',
            'Tarif/Menit',
            'Biaya'
        ];
    }
}
