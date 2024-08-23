<?php

namespace App\Exports;

use App\Models\Blasting;
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
        $reports = Blasting::with('deliveryReport')
            ->select('BroadcastDate', 'DeliveryDateTime', 'ReceiverName', 'MobileNo', 'Message', 'TxReference', 'deliveryReport.chargable')
            ->where('SendNow', 'F')
            ->whereNotNull('DeliveryDateTime')
            ->get();
        // $testing = DeliveryReport::all(['id', 'transid', 'referenceid', 'chargable', 'drsource', 'status', 'description'])
        //     ->with('mBlasting');

        // dd($testing);

        return $reports;
        // return DeliveryReport::all(['id', 'transid', 'referenceid', 'chargable', 'drsource', 'status', 'description', 'created_at']);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Schedule Date',
            'Sent Date',
            'Customer Name',
            'Contact',
            'Content',
            'Reference ID',
            'Chargable',
            // 'DR Source',
            // 'Status',
            // 'Remark'
        ];
    }
}