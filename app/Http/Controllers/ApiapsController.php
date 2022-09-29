<?php

namespace App\Http\Controllers;

use App\Jobs\SalesCsvProcess;
use MongoDB\Client as Mongo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiapsController extends Controller
{
    
    public function apiaps(Request $req)
    { // "172.16.8.50", "172.16.16.50"

        $conn =  new Mongo;
        $companydb  = $conn->iparuba;
        $tem = $companydb->temporary;


        $mon = new Mongo;
        $conn = $mon->iparuba->ipmaster;
        $data = $conn->find()->toArray();


        for ($r = 0; $r < count($data); $r++) {
           
            $ip = $data[$r]['ip'];
            //echo $ip . "<br>";
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
                $sid = $resp->json()['sid'];
                
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json;charset=UTF-8'
                ])
                    ->withOptions(["verify" => false])
                    ->get('https://' . $ip . ':4343/rest/show-cmd?iap_ip_addr=' . $ip . '&cmd=show%20aps&sid=' . $sid);
               
                 
                $ex = explode('\n', $response);

              

                for ($x = 9; $x < count($ex); $x++) {
                    $keywords = preg_split("/[\s,]+/", $ex[$x]);
                

                   if(strlen($keywords[0]) == 1 ){
                      $keywords[0] = null;
                   }
                   else if($keywords[0] != null){
                        $keywords[0];
                   }
                   echo $keywords[0];
                   
                    
                }
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                //echo $ip." offline";
            }
        }

        
       
    }
}
