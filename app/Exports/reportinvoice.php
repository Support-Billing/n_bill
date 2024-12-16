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

class ReportInvoice implements FromCollection, WithEvents, ShouldAutoSize, WithStyles, WithHeadings
{
    use Exportable;

    protected $_data_result;
    protected $_title = '-';
    protected $_dateRange = '';
    protected $_All_JumlahIP = 0;
    protected $_All_WaktuReal = 0;
    protected $_All_TotalPrice = 0;
    protected $_DataProject = 0;
    
    public function __construct($data_result, $idxCore, $ftDateStart, $ftDateEnd, $DataProject)
    {
        // echo $ftDateEnd;exit;
        $this->_data_result = $data_result;
        $this->_DataProject = $DataProject;
        
        if (empty($idxCore)) {
            $this->_title = 'Report Invoice';
        }else{
            if (isset($data_result[3]['projectAlias'])) {
                $this->_title = $data_result[3]['projectAlias'];
            }
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
                // print_r($cdr_data);exit;
                $biaya = abs($cdr_data['totalbiayaTagih']);
                $biayaTelkom = abs($cdr_data['totalbiayaTelkom']);
                $penghematan = $biayaTelkom - $biaya;
                
                if ($biayaTelkom != 0) {
                    $penghematanPercentage = ($penghematan / $biayaTelkom) * 100;
                    $showPenghematan = number_format($penghematanPercentage, 2) . '%';
                } else {
                    $showPenghematan = "-"; // Atau pesan lain yang sesuai dengan kebutuhan Anda
                }

                $idxCoreProject = $cdr_data['idxCoreProject'];
                $projectAlias = '';
                if (isset($this->_DataProject[$idxCoreProject])) {
                    $projectAlias = $this->_DataProject[$idxCoreProject];
                }

                return [
                    'tanggal'   => $cdr_data['date'],
                    'Tujuan'    => $projectAlias,
                    'Waktu Real'    => $cdr_data['totalWaktuReal'],
                    'Waktu Tagih'    => $cdr_data['totalWaktuTagih'],
                    'Tarif'    => $cdr_data['custPrice'],
                    'Biaya'    => $cdr_data['totalbiayaTagih'],
                    'kosong'    => '',
                    'Tarif Telkom'    => $cdr_data['tarifTelkom'],
                    'Biaya Telkom'    => $cdr_data['totalbiayaTelkom'],
                    'Penghematan'    => $showPenghematan,
                ];
                
            }else{
                return [
                    'tanggal'      => ''
                ];

            }
        });
    }

    public function collection_old()
    {
        // Konversi array menjadi koleksi (collection)
        $data_result = collect($this->_data_result);
        
        $mapped_data = $data_result->map(function ($cdr_data) {
            print_r ($cdr_data);exit;
            if (isset($cdr_data['projectAlias'])) {
                
                if (isset($this->_DataProject[$sumIdxCoreProject])) {
                    $projectAlias = $this->_DataProject[$sumIdxCoreProject];
                }
                
                $biaya = abs($cdr_data['biayaTagih']);
                $biayaTelkom = abs($cdr_data['biayaTelkom']);
                $penghematan = $biayaTelkom - $biaya;
                
                if ($biayaTelkom != 0) {
                    $penghematanPercentage = ($penghematan / $biayaTelkom) * 100;
                    $showPenghematan = number_format($penghematanPercentage, 2) . '%';
                } else {
                    $showPenghematan = "-"; // Atau pesan lain yang sesuai dengan kebutuhan Anda
                }

                return [
                    'tanggal'   => $cdr_data['date'],
                    'Tujuan'    => $cdr_data['idxCoreProject'],
                    'Waktu Real'    => $cdr_data['jmlWaktuReal'],
                    'Waktu Tagih'    => $cdr_data['jmlWaktuTagih'],
                    'Tarif'    => '-',
                    'Biaya'    => $cdr_data['biayaTagih'],
                    'kosong'    => '',
                    'Tarif Telkom'    => $cdr_data['tarifTelkom'],
                    'Biaya Telkom'    => $cdr_data['biayaTelkom'],
                    'Penghematan'    => $showPenghematan,
                ];
            } else {
                return [
                    'tanggal'      => ''
                ];
            }
            
        });
    
        // Lakukan skip pada data yang sudah dimapping
        // $skipped_data = $mapped_data->skip(4); // Mulai dari baris ke-5
        
        return $mapped_data;
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
                        'color' => ['rgb' => '538DD5'], // Warna font 
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
                        'color' => ['rgb' => '538DD5'] , // Warna font 
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
                $cellRange = 'A4:F4';
                $sheet->getStyle($cellRange)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('538DD5'); // Ganti dengan warna yang diinginkan

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
                    
                // Mengatur style untuk header data
                $cellRange = 'H4:' . $lastColumn . '4';
                $sheet->getStyle($cellRange)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('E26B0A'); // Ganti dengan warna yang diinginkan

                $sheet->getStyle($cellRange)
                    ->getFont()
                    ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE)) // Mengatur warna teks menjadi putih
                    ->setName('Tahoma') // Menetapkan fontFamily ke Arial, ganti dengan fontFamily yang diinginkan
                    ->setSize(8);

                $sheet->getStyle($cellRange)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    

                // ================================ Body Data ================================ //
                $cellRange = 'I5:I'.$highestRow;
                $sheet->getStyle($cellRange)
                    ->getNumberFormat()
                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
                
                $sheet->getStyle($cellRange)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                $cellRange = 'J5:J'.$highestRow;
                $sheet->getStyle($cellRange)
                    ->getNumberFormat()
                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
                
                $sheet->getStyle($cellRange)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    
                $cellRange = 'A5:'.$lastColumn.$highestRow;
                $sheet->getStyle($cellRange)
                    ->getFont()
                    ->setName('Tahoma') // Menetapkan fontFamily ke Arial, ganti dengan fontFamily yang diinginkan
                    ->setSize(8);
                    
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
            'Tanggal',
            'Project',
            'Waktu Real',
            'Waktu Tagih',
            'Tarif',
            'Biaya',
            '',
            'Tarif Telkom',
            'Biaya Telkom',
            'Penghematan'
        ];
    }
}
