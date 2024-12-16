<?php

namespace App\Exports;

use App\Models\project;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithHeadings;


class ProjectExport implements FromCollection, WithEvents, ShouldAutoSize, WithStyles, WithHeadings
{
    use Exportable;
    protected $dataStatus;

    public function __construct($dataStatus)
    {
        $this->dataStatus = $dataStatus;
    }

    public function collection()
    {
        if ($this->dataStatus == 'active') {
            return Project::where('statusData', 1)
                          ->select('projectID', 'projectName')
                          ->get();
        } else {    
            $projects = Project::select('projectID', 'projectName')->get();
    
            return $projects->map(function ($project) {
                return [
                    'projectID'   => $project->projectID,
                    'projectName' => $project->projectName,
                ];
            });
        }
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:' . $event->sheet->getHighestColumn() . '1';

                // Set fill color dan font bold
                $event->sheet->getStyle($cellRange)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFA0A0A0'); // Ganti dengan warna yang diinginkan

                $event->sheet->getStyle($cellRange)->getFont()->setBold(true);
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
