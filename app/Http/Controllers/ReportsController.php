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

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $filters = ['transid', 'description', 'referenceid', 'drsource'];
        $delivery_reports = DeliveryReport::where(function ($query) use ($request, $filters) {
            foreach ($filters as $filter) {
                if ($request->has('search')) {
                    $query->orWhere($filter, 'like', '%' . $request->search . '%');
                }
            }
        })
        ->paginate(10);

        foreach ($delivery_reports as $key => $dr) {
            $delivery_reports[$key]->broadcast = Blasting::where('TxReference', $dr->referenceid)->first();
        }

        return view('reports.index', compact('delivery_reports'));
    }

    public function downloadExcel()
    {
        return Excel::download(new ReportsExport, 'delivery_reports.xlsx');
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
}
