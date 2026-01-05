<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $reportType;
    protected $data;

    public function __construct($reportType, $data)
    {
        $this->reportType = $reportType;
        $this->data = $data;
    }

    public function collection()
    {
        // Return collection based on report type
        switch($this->reportType) {
            case 'animals':
                return $this->data['animals'];
            case 'milk_production':
                return $this->data['records'];
            case 'health_records':
                return $this->data['records'];
            case 'breeding_records':
                return $this->data['records'];
            default:
                return collect();
        }
    }

    public function headings(): array
    {
        switch($this->reportType) {
            case 'animals':
                return ['Animal ID', 'Name', 'Breed', 'Status', 'Date of Birth', 'Date Added', 'Sex', 'Source'];
            case 'milk_production':
                return ['Date', 'Animal ID', 'Morning Yield', 'Evening Yield', 'Total Yield', 'Lactation Number', 'Status'];
            case 'health_records':
                return ['Date', 'Animal ID', 'Diagnosis', 'Treatment', 'Veterinarian', 'Outcome', 'Notes'];
            case 'breeding_records':
                return ['Service Date', 'Animal ID', 'Breeding Method', 'Pregnancy Result', 'Expected Calving', 'Actual Calving'];
            default:
                return [];
        }
    }

    public function map($row): array
    {
        switch($this->reportType) {
            case 'animals':
                return [
                    $row->animal_id,
                    $row->name,
                    $row->breed,
                    $row->status,
                    $row->date_of_birth ? $row->date_of_birth->format('Y-m-d') : '',
                    $row->date_added ? $row->date_added->format('Y-m-d') : '',
                    $row->sex,
                    $row->source,
                ];
            case 'milk_production':
                return [
                    $row->date->format('Y-m-d'),
                    $row->animal->animal_id,
                    $row->morning_yield,
                    $row->evening_yield,
                    $row->total_yield,
                    $row->lactation_number,
                    $row->status,
                ];
            case 'health_records':
                return [
                    $row->date->format('Y-m-d'),
                    $row->animal->animal_id,
                    $row->diagnosis,
                    $row->treatment,
                    $row->veterinarian,
                    $row->outcome,
                    $row->notes,
                ];
            case 'breeding_records':
                return [
                    $row->date_of_service->format('Y-m-d'),
                    $row->animal->animal_id,
                    $row->breeding_method,
                    $row->pregnancy_result ? 'Yes' : 'No',
                    $row->expected_calving_date ? $row->expected_calving_date->format('Y-m-d') : '',
                    $row->actual_calving_date ? $row->actual_calving_date->format('Y-m-d') : '',
                ];
            default:
                return [];
        }
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A:Z' => ['alignment' => ['wrapText' => true]],
        ];
    }
}