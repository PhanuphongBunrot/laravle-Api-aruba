<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use MongoDB\Client as Mongo;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class IPgroupController extends Controller
{
    public function ipgroup()
    {
        error_reporting(E_ALL ^ E_NOTICE);
        $mon = new Mongo;
        $conn = $mon->iparuba->ipgroup;
        $ip_group = $conn->find()->toArray();

        $mon = new Mongo;
        $conn = $mon->iparuba->ipmaster;
        $data = $conn->find()->toArray();

        $conn =  new Mongo;
        $companydb  = $conn->iparuba;
        $ipg = $companydb->ipgroup;

        $num = 0;
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
                $sid = $resp->json()['sid'];

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json;charset=UTF-8'
                ])
                    ->withOptions(["verify" => false])
                    ->get('https://' . $ip . ':4343/rest/show-cmd?iap_ip_addr=' . $ip . '&cmd=show%20aps&sid=' . $sid);


                $ex = explode('\n', $response);



                for ($x = 9; $x < count($ex); $x++) {
                    $keywords = preg_split("/[\s,]+/", $ex[$x]);


                    if (strlen($keywords[0]) == 1) {
                        $keywords[0] = null;
                    } else if ($keywords[0] != null) {
                            
                        if (count($ip_group) == 0) {
                            $inser = $ipg->insertMany([
                                [
                                    $ip => $keywords[0],

                                ]
                            ]);
                        }
                        $key [] =  $keywords[0];
                        $key_ip[] = [$ip => $keywords[0]];
                    }
                }

                $num = 0;
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                //echo $ip." offline";
            }
        }

           
            for($p =0 ; $p < count($arr_ip); $p++){
                for($g = 0 ; $g < count($ip_group) ; $g++){
                    if ($ip_group[$g][$arr_ip[$p]] != null){
                        $tyy[]=  $ip_group[$g][$arr_ip[$p]];
                    }
                   
                }
            }
             $re = array_diff($key, $tyy);
            
             for($l =0 ; $l < count($arr_ip); $l++){
             for ($k =0 ; $k < count($key_ip) ; $k ++){
                if($re[$k] != null ){
                    if($re[$k] == $key_ip[$k][$arr_ip[$l]])
                        //echo $arr_ip[$l]." ". $key_ip[$k][$arr_ip[$l]];
                        $inser = $ipg->insertMany([
                            [
                                $arr_ip[$l] => $key_ip[$k][$arr_ip[$l]],

                            ]
                        ]);
                   }
                }
            }
            
    }
}
