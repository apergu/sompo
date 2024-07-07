<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomersExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Customer::all(['id', 'zd_id', 'name', 'email', 'tags', 'created_at', 'updated_at']);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'ZD ID',
            'Name',
            'Email',
            'Tags',
            'Created At',
            'Updated At',
        ];
    }
}
