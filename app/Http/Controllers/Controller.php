<?php

namespace App\Http\Controllers;

use Debugger;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
        $this->base_url = env('IMS_API_URL');
        $this->ims_regular_user = env('IMS_REGULAR_USER');
        $this->ims_regular_pass = env('IMS_REGULAR_PASS');
        $this->ims_premium_user = env('IMS_PREMIUM_USER');
        $this->ims_premium_pass = env('IMS_PREMIUM_PASS');
        $this->ssh_host = env('SSH_HOST');
        $this->ssh_port = env('SSH_PORT');
        $this->ssh_user = env('SSH_USER');
        $this->ssh_pass = env('SSH_PASS');
        $this->brand = 'SOMPO';
    }

    public function req($method, $endpoint, $data = array(), $token = false, $trial = 0)
    {
        try {
            $request_config = [];
            $request_config['headers']['Content-Type'] = 'application/json';
            $request_config['headers']['Access-Control-Allow-Origin'] = '*';

            switch ($method) {
                case 'GET':
                    $response = $this->client->request($method, $this->base_url.$endpoint, $request_config);
                    $response = $response->getBody()->getContents();
                    $response = json_decode($response, true);
                break;
                case 'POST':
                    $request_config['body'] = json_encode($data);
                    $response = $this->client->request($method, $this->base_url.$endpoint, $request_config);
                    $response = $response->getBody()->getContents();
                    $response = json_decode($response, true);
                break;
                case 'PUT':
                    $request_config['body'] = json_encode($data);
                    $response = $this->client->request($method, $this->base_url.$endpoint, $request_config);
                    $response = $response->getBody()->getContents();
                    $response = json_decode($response, true);
                break;
                default:
                    $response = (object) ['json' => ['response_code' => 500]];
            }

            return $response;
        } catch (ClientException $e) {
            Debugar::addThrowable($e);

            if ($e->hasResponse()) {
                $response = $e->getResponse();

                return json_decode($response->getBody(), true);
            }
        } catch (GuzzleHttp\Exception\RequestException $e) {
            Debugar::addThrowable($e);

            return json_decode($e->getResponse()->getBody(), true);
        } catch (\Exception $e) {
            Debugar::addThrowable($e);

            return json_decode($e->getResponse()->getBody(), true);
        }
    }
}
