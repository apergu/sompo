<?php

namespace App\Exports;

use App\Models\DeliveryReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportsExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return DeliveryReport::all(['id', 'transid', 'referenceid', 'chargable', 'drsource', 'status', 'description', 'created_at']);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Transaction ID',
            'Reference ID',
            'Chargable',
            'DR Source',
            'Status',
            'Description',
            'Created At'
        ];
    }
}