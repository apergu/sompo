<?php

namespace App\Http\Controllers;

use Exception;

use App\Models\Blasting;
use App\Services\SshService;
use Illuminate\Http\Request;

class BlastingController extends Controller
{
    //
    public function index()
    {
        try {
            $blasting = Blasting::where([
                'SendNow' => 'Y'
            ])->get();

            if (count($blasting) > 0) {
                return response()->json($blasting);
            }

            return response()->json($blasting);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function test()
    {
        $host = $this->ssh_host;
        $port = $this->ssh_port;
        $username = $this->ssh_user;
        $password = $this->ssh_pass;
        $url = $this->base_url."/sendsms/v2";
        $postData = json_encode("{'loginid': 'myloginid', 'password': 'fs#examplepassword', 'sender': 'myBrand', 'msisdn': '629111111', 'msg': 'hello', 'referenceid': 'abcdefg'}");
        $command = "curl --request POST --url '$url' --header 'Content-Type: application/json' --data '$postData'";
        
        try {
            $sshService = new SshService($host, $port, $username, $password);
            $output = $sshService->execute($command);

            return response()->json([
                'code' => 0,
                'message' => $output
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
