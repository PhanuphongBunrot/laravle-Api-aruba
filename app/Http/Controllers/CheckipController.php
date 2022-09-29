<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use MongoDB\Client as Mongo;
use Illuminate\Http\Request;

class CheckipController extends Controller
{
     public function  Ckeck(){
        error_reporting(E_ALL ^ E_NOTICE);
       
        $mon = new Mongo;
        $conn = $mon->iparuba->ipmaster;
        $data = $conn->find()->toArray();

        

        
        for ($r = 0; $r < count($data); $r++) {


            $ip = $data[$r]['ip'];

            $arr_ip[] = $data[$r]['ip'];


            // echo "/".$ip;
            //dd($ip);
            try {
                $resp = Http::timeout(5)->withHeaders([
                    'Content-Type' => 'application/json;charset=UTF-8'
                ])
                    ->withOptions(["verify" => false])
                    ->post('https://' . $ip . ':4343/rest/login', [
                        'user' => 'admin',
                        'passwd' => 'ssit1234'
                    ]);
                 echo $ip ."Online";

               
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                echo $ip." offline";
            }
        }
     }
}
