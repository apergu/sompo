<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DB;
use Exception;
use Str;

use App\Models\Blasting;
use App\Services\SshService;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;

class BlastingController extends Controller
{
    //
    public function index()
    {
        DB::beginTransaction();
        try {
            $res = [];
            $blasting = Blasting::where([
                'SendNow' => 'Y'
            ])
            ->where('MobileNo', '!=', '')
            ->whereDate('BroadcastDate', Carbon::now()->format('Y-m-d'))
            ->get();

            Log::info("Blasting: ".json_encode($blasting));

            if (count($blasting) > 0) {
                $host = $this->ssh_host;
                $port = $this->ssh_port;
                $username = $this->ssh_user;
                $password = $this->ssh_pass;
                $url = $this->base_url."/sendsms/v2";

                foreach ($blasting as $value) {
                    $txreference = Str::uuid()->toString();
                    $response = Http::post($url, [
                        'loginid' => $this->ims_premium_user,
                        'password' => $this->ims_premium_pass,
                        'sender' => $this->brand,
                        'msisdn' => $value['MobileNo'],
                        'msg' => $value['Message'],
                        'referenceid' => $value['TxReference'] !== '' ? $value['TxReference'] : $txreference
                    ]);

                    // $postData = json_encode('{"loginid": "'.$this->ims_premium_user.'", "password": "'.$this->ims_premium_pass.'", "sender": "'.$this->brand.'", "msisdn": "6287887252018", "msg": "'.$value['Message'].'", "referenceid": "'.$value['TxReference'].'"}');
                    // $command = "curl --request POST --url $url --header 'Content-Type: application/json' --data $postData";

                    // $sshService = new SshService($host, $port, $username, $password);
                    // $output = $sshService->execute($command);

                    // // Extract JSON from the output
                    // preg_match('/\{.*\}/s', $output, $matches);
                    // $jsonOutput = $matches[0] ?? null;
                    // $response = json_decode($jsonOutput, true);

                    $update = Blasting::where('BroadcastID', $value['BroadcastID'])->first();
                    $update->SendNow = 'F';
                    $update->DeliveryDateTime = $response['ResultCode'] === 1 ? Carbon::now() : null;
                    if ($value['TxReference'] === '') {
                        $update->TxReference = $txreference;
                    }
                    $update->update();

                    array_push($res, $response->json());
                }

                DB::commit();

                return response()->json($res);
            }

            return response()->json($res);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
