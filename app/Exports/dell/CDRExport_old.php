<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Cdr;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CdrExport implements FromCollection, WithEvents, ShouldAutoSize, WithStyles, WithHeadings
{
    protected $dataCDR;

    public function __construct($dataCDR)
    {
        $this->dataCDR = $dataCDR;
    }

    public function collection()
    {
        return $this->dataCDR;

        
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:' . $event->sheet->getHighestColumn() . '1';

                $event->sheet->getStyle($cellRange)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFA0A0A0');
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
        return ['Project ID', 'Project Name'];
    }
}
