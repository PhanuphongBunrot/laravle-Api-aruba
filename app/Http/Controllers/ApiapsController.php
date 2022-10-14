<?php

namespace App\Http\Controllers;

use App\Jobs\SalesCsvProcess;
use MongoDB\Client as Mongo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiapsController extends Controller
{
    
    public function apiaps(Request $req)
    { 
            try {
                // $resp = Http::timeout(5)->withHeaders([
                //     'Content-Type' => 'application/json;charset=UTF-8'
                // ])
                //     ->withOptions(["verify" => false])
                //     ->post('https://' . $ip . ':4343/rest/login', [
                //         'user' => 'admin',
                //         'passwd' => 'ssit1234'
                //     ]);
                // $sid = $resp->json()['sid'];
                
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json;charset=UTF-8'
                ])
                    ->withOptions(["verify" => false])
                    ->get('https://admin:admin@192.168.207.5/rest/system/resource');
                      $data  = json_decode($response,true) ;
                    echo ($data['build-time']);
               
              
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                //echo $ip." offline";
            }
        

        
       
    }
}
