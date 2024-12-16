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

class ReportInvoicedetil implements FromCollection, WithEvents, ShouldAutoSize, WithStyles, WithHeadings
{
    use Exportable;


    protected $_data_result;
    protected $_title = '-';
    protected $_dateRange = '';
    protected $_All_WaktuReal = 0;
    protected $_date_WaktuReal = 0;
    protected $_All_WaktuTagih = 0;
    protected $_date_WaktuTagih = 0;
    protected $_All_biayaTagih = 0;
    protected $_date_biayaTagih = 0;
    protected $_All_biayaTelkom = 0;
    protected $_date_biayaTelkom = 0;
    
    protected $_DataProject = 0;
    protected $_temp_Tanggal = 0;
    protected $_temp_Tujuan = 0;
    protected $_hitung_kolom = 4;
    protected $_colorCollection = array();
    protected $_colorCollection_s = '';
    
    public function __construct($data_result, $projectAlias, $ftDateStart, $ftDateEnd, $DataProject)
    {
        // echo $ftDateEnd;exit;
        $this->_data_result = $data_result;
        $this->_DataProject = $DataProject;
        $this->_title = $projectAlias;
        
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
        return $data_result->map(function ($cdr_data, $index) use ($data_result) {

            if($cdr_data != 'a'){
                
                $this->_date_WaktuReal = $this->_date_WaktuReal + $cdr_data['totalWaktuReal'];
                $this->_date_WaktuTagih = $this->_date_WaktuTagih + $cdr_data['totalWaktuTagih'];
                $this->_date_biayaTagih = $this->_date_biayaTagih + $cdr_data['totalbiayaTagih'];
                $this->_date_biayaTelkom = $this->_date_biayaTelkom + $cdr_data['totalbiayaTelkom'];

                $this->_All_WaktuReal = $this->_All_WaktuReal + $cdr_data['totalWaktuReal'];
                $this->_All_WaktuTagih = $this->_All_WaktuTagih + $cdr_data['totalWaktuTagih'];
                $this->_All_biayaTagih = $this->_All_biayaTagih + $cdr_data['totalbiayaTagih'];
                $this->_All_biayaTelkom = $this->_All_biayaTelkom + $cdr_data['totalbiayaTelkom'];
                
                $show_tanggal = '';
                $give_data = [];

                $biaya = abs($cdr_data['totalbiayaTagih']);
                $biayaTelkom = abs($cdr_data['totalbiayaTelkom']);
                $penghematan = $biayaTelkom - $biaya;
                
                if ($biayaTelkom != 0) {
                    $penghematanPercentage = ($penghematan / $biayaTelkom) * 100;
                    $showPenghematan = number_format($penghematanPercentage, 2) . '%';
                } else {
                    $showPenghematan = "-"; // Atau pesan lain yang sesuai dengan kebutuhan Anda
                }

                // Cek apakah ada data berikutnya
                $nextData = $data_result->get($index + 1);
                // Jika $nextData tidak null, lakukan perbandingan dengan $cdr_data
                if (is_null($nextData)) {

                    $this->_hitung_kolom += 2;
                    $this->_colorCollection_s = $this->_colorCollection_s.','.$this->_hitung_kolom;
                    $this->_colorCollection_s = $this->_colorCollection_s.','.($this->_hitung_kolom+2);

                    $showPenghematanAll = '-';
                    if ($biayaTelkom != 0) {
                        $penghematanAll = $this->_All_biayaTelkom - $this->_All_biayaTagih;
                        $penghematanPercentageAll = ($penghematanAll / $this->_All_biayaTelkom) * 100;
                        $showPenghematanAll = number_format($penghematanPercentageAll, 2) . '%';
                    }
                    $showPenghematanDate = '-';
                    if ($biayaTelkom != 0) {
                        $penghematanDate = $this->_date_biayaTelkom - $this->_date_biayaTagih;
                        $penghematanPercentageDate = ($penghematanDate / $this->_date_biayaTelkom) * 100;
                        $showPenghematanDate = number_format($penghematanPercentageDate, 2) . '%';
                    }
                    $give_data = [ 
                        [
                            // 'tanggal' => (!empty($show_tanggal)) ? date('d/m/Y', strtotime($show_tanggal)) : '',
                            'tanggal' => date('d/m/Y', strtotime($cdr_data['date'])),
                            'Tujuan'    => $cdr_data['destNoPrefixName'],
                            'Waktu Real' => $cdr_data['totalWaktuReal'],
                            'Waktu Tagih' => $cdr_data['totalWaktuTagih'],
                            'Tarif' => $cdr_data['custPrice'],
                            'Biaya' => $cdr_data['totalbiayaTagih'],
                            'kosong' => '',
                            'Tarif Telkom' => $cdr_data['tarifTelkom'],
                            'Biaya Telkom' => $cdr_data['totalbiayaTelkom'],
                            'Penghematan' => $showPenghematan
                        ],
                        [
                            // 'tanggal' => 'Total '. $this->_temp_Tanggal . '===>' . $this->_hitung_kolom ,
                            'tanggal' => 'Total '. date('d/m/Y', strtotime($this->_temp_Tanggal)) ,
                            'Tujuan' => '',
                            'Waktu Real' => $this->_date_WaktuReal,
                            'Waktu Tagih' => $this->_date_WaktuTagih,
                            'Tarif' => '',
                            'Biaya' => $this->_date_biayaTagih,
                            'kosong' => '',
                            'Tarif Telkom' => '',
                            'Biaya Telkom' => $this->_date_biayaTelkom,
                            'Penghematan' => $showPenghematanDate
                        ],[ 
                            'tanggal' => '',
                            'Tujuan' => '',
                            'Waktu Real' => '',
                            'Waktu Tagih' => '',
                            'Tarif' => '',
                            'Biaya' => '',
                            'kosong' => '',
                            'Tarif Telkom' => '',
                            'Biaya Telkom' => '',
                            'Penghematan' => '',
                        ],[ 
                            'tanggal'   => 'Total '.$this->_title,
                            // 'tanggal' => 'Total ',
                            'Tujuan' => '',
                            'Waktu Real' => $this->_All_WaktuReal,
                            'Waktu Tagih' => $this->_All_WaktuTagih,
                            'Tarif' => '',
                            'Biaya' => $this->_All_biayaTagih,
                            'kosong' => '',
                            'Tarif Telkom' => '',
                            'Biaya Telkom' => $this->_All_biayaTelkom,
                            'Penghematan' => $showPenghematanAll
                        ]
                    ];
                }else{

                    // untuk tanggal
                    $show_tanggal = $cdr_data['date'];
                    $this->_temp_Tanggal = $cdr_data['date'] ;
                    if($cdr_data['date'] != $this->_temp_Tanggal){
                        $this->_date_WaktuReal = $cdr_data['totalWaktuReal'];
                        $this->_date_WaktuTagih = $cdr_data['totalWaktuTagih'];
                        $this->_date_biayaTagih = $cdr_data['totalbiayaTagih'];
                        $this->_date_biayaTelkom = $cdr_data['totalbiayaTelkom'];

                    }

                    // Contoh perbandingan
                    if ($cdr_data['date'] != $nextData['date']) {
                        
                        $this->_hitung_kolom += 2;
                        if(empty($this->_colorCollection_s)){
                            $this->_colorCollection_s = $this->_hitung_kolom;
                        }else{
                            $this->_colorCollection_s = $this->_colorCollection_s.','.$this->_hitung_kolom;
                        }
                        
                        $showPenghematanDate = '-';
                        if ($biayaTelkom != 0) {
                            $penghematanDate = $this->_date_biayaTelkom - $this->_date_biayaTagih;
                            $penghematanPercentageDate = ($penghematanDate / $this->_date_biayaTelkom) * 100;
                            $showPenghematanDate = number_format($penghematanPercentageDate, 2) . '%';
                        }
                        
                        $give_data = [ 
                            [
                                'tanggal' => (!empty($show_tanggal)) ? date('d/m/Y', strtotime($show_tanggal)) : '',
                                'Tujuan'    => $cdr_data['destNoPrefixName'] ,
                                'Waktu Real' => $cdr_data['totalWaktuReal'],
                                'Waktu Tagih' => $cdr_data['totalWaktuTagih'],
                                'Tarif' => $cdr_data['custPrice'],
                                'Biaya' => $cdr_data['totalbiayaTagih'],
                                'kosong' => '',
                                'Tarif Telkom' => $cdr_data['tarifTelkom'],
                                'Biaya Telkom' => $cdr_data['totalbiayaTelkom'],
                                'Penghematan' => $showPenghematan
                            ],
                            [
                                // 'tanggal' => 'Total '. $this->_temp_Tanggal . '===>' . $this->_hitung_kolom ,
                                // 'tanggal' => 'Total '. $this->_temp_Tanggal ,
                                'tanggal' => 'Total '. date('d/m/Y', strtotime($this->_temp_Tanggal)),
                                'Tujuan' => '',
                                'Waktu Real' => $this->_date_WaktuReal,
                                'Waktu Tagih' => $this->_date_WaktuTagih,
                                'Tarif' => '',
                                'Biaya' => $this->_date_biayaTagih,
                                'kosong' => '',
                                'Tarif Telkom' => '',
                                'Biaya Telkom' => $this->_date_biayaTelkom,
                                'Penghematan' => $showPenghematanDate
                            ]
                        ];
                    
                    }else{

                        $this->_hitung_kolom++;
                        $show_tanggal = $cdr_data['date'];
                        $this->_temp_Tanggal = $cdr_data['date'] ;
                        
                        $give_data = [ 
                            // 'tanggal'   => $show_tanggal .'  === '. $this->_hitung_kolom,
                            // 'tanggal' => (!empty($show_tanggal)) ? date('d/m/Y', strtotime($show_tanggal)) : '',
                            'tanggal' => date('d/m/Y', strtotime($show_tanggal)),
                            'Tujuan' => $cdr_data['destNoPrefixName'],
                            'Waktu Real' => $cdr_data['totalWaktuReal'],
                            'Waktu Tagih' => $cdr_data['totalWaktuTagih'],
                            'Tarif' => $cdr_data['custPrice'],
                            'Biaya' => $cdr_data['totalbiayaTagih'],
                            'kosong' => '',
                            'Tarif Telkom' => $cdr_data['tarifTelkom'],
                            'Biaya Telkom' => $cdr_data['totalbiayaTelkom'],
                            'Penghematan' => $showPenghematan,
                        ];
                    }
                }

                return $give_data;
                
            }else{
                return [
                    'tanggal' => ''
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
                $sheet->mergeCells('A1:' . $lastColumn . '2');

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

                // Menggabungkan sel untuk rentang tanggal
                $sheet->mergeCells('A3:' . $lastColumn . '3');

                // isi data tanggal
                $sheet->setCellValue('A3', $this->_dateRange);
                $sheet->getStyle('A3')->applyFromArray([
                    'font' => [
                        'bold' => false,
                        'size' => 10, // Ukuran font (misalnya 14pt)
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
                // $headings = $this->headings();
                // foreach ($headings as $index => $heading) {
                //     $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
                //     $sheet->setCellValue($column . '4', $heading);
                // }
                // // Mengatur style untuk header data
                // $cellRange = 'A4:F4';
                // $sheet->getStyle($cellRange)
                //     ->getFill()
                //     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                //     ->getStartColor()->setARGB('4682B4'); // Ganti dengan warna yang diinginkan

                // $sheet->getStyle($cellRange)
                //     ->getFont()
                //     ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE)) // Mengatur warna teks menjadi putih
                //     ->setName('Tahoma') // Menetapkan fontFamily ke Arial, ganti dengan fontFamily yang diinginkan
                //     ->setSize(8);

                // $sheet->getStyle($cellRange)
                //     ->getAlignment()
                //     ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                //     // HORIZONTAL_CENTER
                //     // HORIZONTAL_LEFT
                //     // HORIZONTAL_RIGHT
                //     // HORIZONTAL_JUSTIFY
                $headings = $this->headings();
                foreach ($headings as $index => $heading) {
                    $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
                    $sheet->setCellValue($column . '4', $heading);
                }

                // Mengatur style untuk header data
                $cellRange = 'A4:F4'; // Sesuaikan dengan rentang sel yang ingin diatur
                // Mengatur warna background header
                $sheet->getStyle($cellRange)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('4682B4'); // Ganti dengan warna yang diinginkan

                // Mengatur style font (bold, warna, ukuran, font family)
                $sheet->getStyle($cellRange)
                    ->getFont()
                    ->setBold(true) // Menambahkan bold pada teks
                    ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE)) // Mengatur warna teks menjadi putih
                    ->setName('Tahoma') // Menetapkan fontFamily ke Tahoma
                    ->setSize(8); // Mengatur ukuran font

                // Mengatur alignment (rata tengah)
                $sheet->getStyle($cellRange)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // Mengatur rata tengah vertikal juga (opsional)

                    
                    
                // Mengatur style untuk header data
                // $cellRange = 'H4:' . $lastColumn . '4';
                // $sheet->getStyle($cellRange)
                //     ->getFill()
                //     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                //     ->getStartColor()->setARGB('E26B0A'); // Ganti dengan warna yang diinginkan

                // $sheet->getStyle($cellRange)
                //     ->getFont()
                //     ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE)) // Mengatur warna teks menjadi putih
                //     ->setName('Tahoma') // Menetapkan fontFamily ke Arial, ganti dengan fontFamily yang diinginkan
                //     ->setSize(8);

                // $sheet->getStyle($cellRange)
                //     ->getAlignment()
                //     ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    
                // Mengatur style untuk header data
                $cellRange = 'H4:' . $lastColumn . '4'; // Sesuaikan dengan rentang sel yang ingin diatur
                // Mengatur warna background header
                $sheet->getStyle($cellRange)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('C65911'); // Ganti dengan warna yang diinginkan

                // Mengatur style font (bold, warna, ukuran, font family)
                $sheet->getStyle($cellRange)
                    ->getFont()
                    ->setBold(true) // Menambahkan bold pada teks
                    ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE)) // Mengatur warna teks menjadi putih
                    ->setName('Tahoma') // Menetapkan fontFamily ke Tahoma
                    ->setSize(8); // Mengatur ukuran font

                // Mengatur alignment (rata tengah)
                $sheet->getStyle($cellRange)
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // Mengatur rata tengah vertikal juga (opsional)


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


                $dataArraycolorCollection = explode(',', $this->_colorCollection_s);

                foreach ($dataArraycolorCollection as $valuecolorCollection) {
                    $sheet->mergeCells('A'.$valuecolorCollection.':B'.$valuecolorCollection);

                    // Mengatur style untuk header data
                    $cellRange = 'A'.$valuecolorCollection.':F'.$valuecolorCollection;
                    $sheet->getStyle($cellRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('6E9ECA');

                    $sheet->getStyle($cellRange)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE)) // Mengatur warna teks menjadi putih
                        ->setName('Tahoma')
                        ->setSize(8);

                    $sheet->getStyle('A'.$valuecolorCollection)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $sheet->getStyle('B'.$valuecolorCollection.':F'.$valuecolorCollection)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    
                    // Mengatur style untuk header data
                    
                    $cellRange = 'H'.$valuecolorCollection.':'.$lastColumn.$valuecolorCollection;
                    $sheet->getStyle($cellRange)->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('F4B084'); // Ganti dengan warna yang diinginkan

                    $sheet->getStyle($cellRange)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE)) // Mengatur warna teks menjadi putih
                        ->setName('Tahoma') // Menetapkan fontFamily ke Arial, ganti dengan fontFamily yang diinginkan
                        ->setSize(8);

                    $sheet->getStyle($cellRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                    $sheet->getStyle('H'.$valuecolorCollection)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $sheet->getStyle('I'.$valuecolorCollection.':'.$lastColumn.$valuecolorCollection)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    
                }
                
                // Memastikan AutoSize dimatikan
                $sheet->getColumnDimension('A')->setAutoSize(false);
                $sheet->getColumnDimension('B')->setAutoSize(false);
                $sheet->getColumnDimension('C')->setAutoSize(false);
                $sheet->getColumnDimension('D')->setAutoSize(false);
                $sheet->getColumnDimension('E')->setAutoSize(false);
                $sheet->getColumnDimension('F')->setAutoSize(false);
                $sheet->getColumnDimension('G')->setAutoSize(false);
                $sheet->getColumnDimension('H')->setAutoSize(false);
                $sheet->getColumnDimension('I')->setAutoSize(false);
                $sheet->getColumnDimension('J')->setAutoSize(false);

                // Mengatur lebar kolom secara manual
                $sheet->getColumnDimension('A')->setWidth(15);
                $sheet->getColumnDimension('B')->setWidth(25);
                $sheet->getColumnDimension('C')->setWidth(15);
                $sheet->getColumnDimension('D')->setWidth(15);
                $sheet->getColumnDimension('E')->setWidth(15);
                $sheet->getColumnDimension('F')->setWidth(15);
                $sheet->getColumnDimension('G')->setWidth(15);
                $sheet->getColumnDimension('H')->setWidth(15);
                $sheet->getColumnDimension('I')->setWidth(15);
                $sheet->getColumnDimension('J')->setWidth(15);

                // Mengatur border hitam untuk range A4:F
                $cellRangeBorder1 = 'A4:F' . ($highestRow - 2);
                $this->setBlackBorder($sheet, $cellRangeBorder1);

                // Mengatur border hitam untuk range H4:J
                $cellRangeBorder2 = 'H4:J' . ($highestRow - 2);
                $this->setBlackBorder($sheet, $cellRangeBorder2);
                

            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    // Fungsi untuk mengatur border hitam pada rentang sel
    public function setBlackBorder($sheet, $cellRange) {
        $sheet->getStyle($cellRange)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
            ->getColor()->setARGB('000000'); // Menetapkan warna hitam
    }
    public function headings(): array
    {
        return [
            'Tanggal',
            'Tujuan',
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
