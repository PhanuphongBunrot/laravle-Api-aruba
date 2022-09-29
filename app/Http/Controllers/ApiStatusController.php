<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Jobs\SalesCsvProcess;
use MongoDB\Client as Mongo;
use Illuminate\Http\Request;
use Acamposm\Ping\Ping;
use Acamposm\Ping\PingCommandBuilder;

class ApiStatusController extends Controller
{
    //
    public function apimaster(Request $request)
    {
        error_reporting(E_ALL ^ E_NOTICE);

       
        $mon = new Mongo;
        $conn = $mon->iparuba->ipmaster;
        $data = $conn->find()->toArray();

        $mon = new Mongo;
        $conn = $mon->iparuba->ipaps;
        $ip_db = $conn->find()->toArray();

      

     
        $o = 0;
        $t = date_default_timezone_set('Asia/Bangkok');
        $t = date('Y-m-d H:i:s');
        $Y = date('Y');
        $m = date('m');
        $d = date('d');
        $h = date('H');
        $min = date('i');


        for ($r = 0; $r < count($data); $r++) {

            $ip = $data[$r]['ip'];

            

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
                        //echo ("/".$keywords[0]);
                        $mac_ap1[] = [  'Max'=>$keywords[0],
                                    'ipap'=>$keywords[1]
                        ] ;
                        
                    }
                }
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
               
            }
        }
       

         echo "<pre>";
         print_r($mac_ap1);
         echo"</pre>";

         for ($xl = 0 ;$xl <count($mac_ap1); $xl++){
            $mac_ap[] = $mac_ap1[$xl]["Max"];
         }

         
       
        for ($l = 0; $l < count($ip_db); $l++) {
            $arr_ip[] = $ip_db[$l]['Max'];
        }

        $re = array_diff($mac_ap, $arr_ip);




        $online = array_intersect($arr_ip, $mac_ap);
        $online = array_values($online);
        for ($k = 0; $k < count($online); $k++) {
            $o = $o + 1;
            
            echo ($online[$k] . " " . $ip_db[$k]['Apname'] . " Online" . " " . $d . "/" . $m . "/" . $Y . " " . $h . ":" . $min  . " " .$mac_ap1[$k]["ipap"]." ");
        }


        $f = 0;

        $offline = array_diff($arr_ip, $mac_ap);

        $offline = array_values($offline);
        for ($g = 0; $g < count($offline); $g++) {
            $f = $f + 1;
            


            echo ($offline[$g] . " " . $ip_db[$g]['Apname'] . " Offline" . " " . $d . "/" . $m . "/" . $Y . " " . $h . ":" . $min  . " ");
        }
    }
    
}
