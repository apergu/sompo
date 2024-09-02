<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DB;
use Exception;

use App\Exports\ReportsExport;
use App\Models\Blasting;
use App\Models\DeliveryReport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $start_date = $request->has('from_date') ? $request->from_date : Carbon::now()->startOfMonth()->format('Y-m-d');
        $end_date = $request->has('to_date') ? $request->to_date : Carbon::now()->endOfMonth()->format('Y-m-d');
        $search = $request->has('search') ? $request->search : '';
        $params = '';

        if ($request->has('from_date') && $request->has('to_date')) {
            $params = '?from_date='.$request->from_date.'&to_date='.$request->to_end.'&search='.$search;
        }

        $filters = ['ReceiverName', 'MobileNo', 'TxReference', 'Message', 'Sompo_Category_SMS.CategoryDescription'];

        $delivery_reports = Blasting::with(['deliveryReport', 'SMSCategory'])
            ->join('Sompo_Category_SMS', 'Broadcast.CategoryID', '=', 'Sompo_Category_SMS.CategoryID')
            ->where(function ($query) use ($request, $filters) {
                foreach ($filters as $filter) {
                    if ($request->has('search')) {
                        $query->orWhere($filter, 'like', '%' . $request->search . '%');
                    }
                }
            })
            ->where('SendNow', 'F')
            ->whereNotNull('DeliveryDateTime')
            ->when($request->has('from_date') && $request->has('to_date'), function ($query) use ($request) {
                $from_date = $request->from_date != null ? Carbon::createFromFormat('Y-m-d', $request->from_date) : Carbon::now()->startOfMonth();
                $to_date = $request->to_date != null ? Carbon::createFromFormat('Y-m-d', $request->to_date) : Carbon::now()->endOfMOnth();

                $query->whereDate('DeliveryDateTime', '>=', $from_date);
                $query->whereDate('DeliveryDateTime', '<=', $to_date);
            })
            ->paginate(10);

        return view('reports.index', compact('delivery_reports', 'start_date', 'end_date', 'search', 'params'));
    }

    public function detailCode($status_code)
    {
        $data = [];

        switch ($status_code) {
            case '0':
                $data = [
                    'chargable' => 'Yes',
                    'description' => 'Success Submission (for no Delivery Report operator)',
                    'dr_source' => 'Operator'
                ];
            break;
            case '2':
                $data = [
                    'chargable' => 'Yes',
                    'description' => 'Delivered',
                    'dr_source' => 'Operator'
                ];
            break;
            case '3':
                $data = [
                    'chargable' => 'Yes',
                    'description' => 'Expire',
                    'dr_source' => 'Operator'
                ];
            break;
            case '5':
                $data = [
                    'chargable' => 'Yes',
                    'description' => 'Undelivered',
                    'dr_source' => 'Operator'
                ];
            break;
            case '7':
                $data = [
                    'chargable' => 'Yes',
                    'description' => 'Unknown DR Status',
                    'dr_source' => 'Operator'
                ];
            break;
            case '8':
                $data = [
                    'chargable' => 'Yes',
                    'description' => 'Rejected',
                    'dr_source' => 'Operator'
                ];
            break;
            case '9':
                $data = [
                    'chargable' => 'No',
                    'description' => 'Undelivered (specific product)',
                    'dr_source' => 'Operator'
                ];
            break;
            case '4001':
                $data = [
                    'chargable' => 'No',
                    'description' => 'SMSC Connection Timeout',
                    'dr_source' => 'Submission'
                ];
            break;
            case '4002':
                $data = [
                    'chargable' => 'Yes',
                    'description' => 'SMSC Wrong Parameter',
                    'dr_source' => 'Submission'
                ];
            break;
            case '4003':
                $data = [
                    'chargable' => 'No',
                    'description' => 'SMSC Fail Authentication',
                    'dr_source' => 'Submission'
                ];
            break;
            case '4004':
                $data = [
                    'chargable' => 'Yes',
                    'description' => 'SMSC Unknown Subscriber',
                    'dr_source' => 'Submission'
                ];
            break;
            case '4005':
                $data = [
                    'chargable' => 'Yes',
                    'description' => 'SMSC Response Timeout',
                    'dr_source' => 'Submission'
                ];
            break;
            case '4008':
                $data = [
                    'chargable' => 'Yes',
                    'description' => 'SMSC Billing Error',
                    'dr_source' => 'Submission'
                ];
            break;
            case '4009':
                $data = [
                    'chargable' => 'Yes',
                    'description' => 'SMSC Unknown Error',
                    'dr_source' => 'Submission'
                ];
            break;
            case '4010':
                $data = [
                    'chargable' => 'Yes',
                    'description' => 'SMSC Blocked Subscriber',
                    'dr_source' => 'Submission'
                ];
            break;
            case '4012':
                $data = [
                    'chargable' => 'No',
                    'description' => 'SMSC Reach Throttle Limit',
                    'dr_source' => 'Submission'
                ];
            break;
            case '4068':
                $data = [
                    'chargable' => 'Yes',
                    'description' => 'SMSC Pending State',
                    'dr_source' => 'Submission'
                ];
            break;
            case '4100':
                $data = [
                    'chargable' => 'No',
                    'description' => 'SMSC Unknown Error',
                    'dr_source' => 'Submission'
                ];
            break;
            case '6017':
                $data = [
                    'chargable' => 'No',
                    'description' => 'SMSC Overloaded Error',
                    'dr_source' => 'Submission'
                ];
            break;
            case '6018':
                $data = [
                    'chargable' => 'No',
                    'description' => 'SMSC Fail After Retries',
                    'dr_source' => 'Submission'
                ];
            break;
            default:
                $data = [
                    'chargable' => '-',
                    'description' => 'Status code not found',
                    'dr_source' => '-'
                ];
        }

        return response()->json($data);
    }

    public function received(Request $request)
    {
        DB::beginTransaction();
        try {
            $transid = $request->transid;
            $statuscode = $request->status;
            $referenceid = $request->referenceid;
            $get_detail_code = $this->detailCode($statuscode);
            $detail_code = json_decode($get_detail_code->getContent(), true);

            if ($detail_code['chargable'] === '-') {
                throw new Exception($detail_code['description']);
            }

            $delivery_reports = new DeliveryReport;
            $delivery_reports->transid = $transid;
            $delivery_reports->status = $statuscode;
            $delivery_reports->referenceid = $referenceid;
            $delivery_reports->description = $detail_code['description'];
            $delivery_reports->chargable = $detail_code['chargable'];
            $delivery_reports->drsource = $detail_code['dr_source'];
            $delivery_reports->created_at = Carbon::now();
            $delivery_reports->save();

            DB::commit();

            return response()->json([
                'status_code' => $statuscode,
                'description' => $detail_code['description'],
                'chargable' => $detail_code['chargable'],
                'dr_source' => $detail_code['dr_source'],
                'trans_id' => $transid
            ]);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'description' => $e->getMessage()
            ]);
        }
    }

    public function downloadExcel(Request $request)
    {
        $filters = ['ReceiverName', 'MobileNo', 'TxReference', 'Message', 'Sompo_Category_SMS.CategoryDescription'];

        $data = Blasting::with(['deliveryReport', 'SMSCategory'])
            ->join('Sompo_Category_SMS', 'Broadcast.CategoryID', '=', 'Sompo_Category_SMS.CategoryID')
            ->where(function ($query) use ($request, $filters) {
                foreach ($filters as $filter) {
                    if ($request->has('search')) {
                        $query->orWhere($filter, 'like', '%' . $request->search . '%');
                    }
                }
            })
            ->where('SendNow', 'F')
            ->whereNotNull('DeliveryDateTime')
            ->when($request->has('from_date') && $request->has('to_date'), function ($query) use ($request) {
                $from_date = $request->from_date != null ? Carbon::createFromFormat('Y-m-d', $request->from_date) : Carbon::now()->startOfMonth();
                $to_date = $request->to_date != null ? Carbon::createFromFormat('Y-m-d', $request->to_date) : Carbon::now()->endOfMOnth();

                $query->whereDate('DeliveryDateTime', '>=', $from_date);
                $query->whereDate('DeliveryDateTime', '<=', $to_date);
            })
            ->get();

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();

        $style_header = array(
            'fill' => array(
                'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => 'BDBDBD'
                )
            ),
            'font' => array(
                'bold' => true,
            )
        );

        $column_data = [
            'No' => 'no',
            'Schedule Date' => 'BroadcastDate',
            'Send Date' => 'DeliveryDateTime',
            'Customer Name' => 'ReceiverName',
            'Contact' => 'MobileNo',
            'Campaign Name' => 'CategoryID',
            'Content' => 'Message',
            'Reference ID' => 'TxReference',
            'Chargable' => 'deliveryReportChargable',
            'DR Source' => 'deliveryReportDRSource',
            'Status' => 'deliveryReportStatus',
            'Remark' => 'deliveryReportRemark'
        ];

        $first_column = 'A';
        $column_letters = [];
        for ($i = 0; $i < count($column_data); $i++) {
            $column_letters[] = $first_column;

            $first_column++;
        }

        for ($col = $column_letters[0]; $col !== end($column_letters); $col++) {
            $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }

        $spreadsheet->getActiveSheet()->mergeCells($column_letters[0].'1:'.end($column_letters).'1');
        $spreadsheet->getActiveSheet()->setCellValueExplicit($column_letters[0].'1', 'DELIVERY REPORTS', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $spreadsheet->getActiveSheet()->getStyle($column_letters[0].'1:'.end($column_letters).'2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle($column_letters[0].'1:'.end($column_letters).'2')->applyFromArray($style_header);

        $i = 0;
        foreach ($column_data as $col_key => $col_value) {
            $spreadsheet->getActiveSheet()->setCellValueExplicit($column_letters[$i].'2', $col_key, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

            $i++;
        }

        $spreadsheet->setActiveSheetIndex(0);
        if (!empty($data)) {
            $no = 1;
            $row = 3;
            $idx_data = 0;
            $letter = $column_letters[0];

            foreach ($data as $value) {
                if ($idx_data == count($column_data)) {
                    $idx_data = 0;
                    $letter = $column_letters[0];
                }

                foreach ($column_data as $col_key => $col_value) {
                    switch ($col_value) {
                        case 'no':
                            $spreadsheet->getActiveSheet()->setCellValueExplicit($letter.$row, $no++, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        break;
                        case 'CategoryID':
                            $val = $value->SMSCategory->CategoryDescription ?? '-';

                            $spreadsheet->getActiveSheet()->setCellValueExplicit($letter.$row, $val, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        break;
                        case 'deliveryReportChargable':
                            $val = $value->deliveryReport->chargable ?? '-';

                            $spreadsheet->getActiveSheet()->setCellValueExplicit($letter.$row, $val, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        break;
                        case 'deliveryReportDRSource':
                            $val = $value->deliveryReport->drsource ?? '-';

                            $spreadsheet->getActiveSheet()->setCellValueExplicit($letter.$row, $val, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        break;
                        case 'deliveryReportStatus':
                            $val = $value->deliveryReport->status ?? '-';

                            $spreadsheet->getActiveSheet()->setCellValueExplicit($letter.$row, $val, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        break;
                        case 'deliveryReportRemark':
                            $val = $value->deliveryReport->description ?? '-';

                            $spreadsheet->getActiveSheet()->setCellValueExplicit($letter.$row, $val, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        break;
                        default:
                            $spreadsheet->getActiveSheet()->setCellValueExplicit($letter.$row, $value[$col_value], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                        break;
                    }

                    $letter++;
                    $idx_data++;
                }

                $row++;
            }
        }

        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                )
            )
        );
        $spreadsheet->getActiveSheet()->getStyle('A1:' .$spreadsheet->getActiveSheet()->getHighestColumn().$spreadsheet->getActiveSheet()->getHighestRow())->applyFromArray($styleArray);

        $writer = new Xlsx($spreadsheet);

        $file_name = 'report-delivery-' . date('YmdHis') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $file_name . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}
