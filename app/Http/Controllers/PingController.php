<?php

namespace App\Http\Controllers;

use App\Jobs\SalesCsvProcess;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use MongoDB\Client as Mongo;

class PingController extends Controller
{
    public function ping()
    {
        error_reporting(E_ALL ^ E_NOTICE);

        $conn =  new Mongo;
        $companydb  = $conn->iparuba;
        $add_db = $companydb->ipaps;

        $conn =  new Mongo;
        $companydb  = $conn->iparuba;
        $up_master = $companydb->ipmaster;

        $mon = new Mongo;
        $conn = $mon->iparuba->ipmaster;
        $data = $conn->find()->toArray();

        $mon = new Mongo;
        $conn = $mon->iparuba->ipaps;
        $ip_db = $conn->find()->toArray();

        $conn =  new Mongo;
        $companydb  = $conn->iparuba;
        $off = $companydb->offline;

     
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

            $updateResult = $up_master->replaceOne(
                ['ip' => $data[$r]['ip']],
                [
                    'ip' => $data[$r]['ip'],
                    'address' => $data[$r]['address'],
                    'Longitude' => $data[$r]['Longitude'],
                    'Latitude' => $data[$r]['Latitude'],
                    'Serial' => $data[$r]['Serial'],
                    'Status' => 'Online',
                    'd/m/y' => $d . "/" . $m . "/" . $Y,
                    'time' => $h . ":" . $min,
                ]

            );

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
                        $mac_ap[] = $keywords[0];
                        
                    }
                }
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                $updateResult = $up_master->replaceOne(
                    ['ip' => $data[$r]['ip']],
                    [
                        'ip' => $data[$r]['ip'],
                        'address' => $data[$r]['address'],
                        'Longitude' => $data[$r]['Longitude'],
                        'Latitude' => $data[$r]['Latitude'],
                        'Serial' => $data[$r]['Serial'],
                        'Status' => 'Offline',
                        'd/m/y' => $d . "/" . $m . "/" . $Y,
                        'time' => $h . ":" . $min,
                    ]
                );
            }
        }
        if (count($ip_db) == 0) {
            for ($i = 0; $i < count($mac_ap); $i++) {
                $inser = $add_db->insertMany([
                    [
                        'Max' => $mac_ap[$i],
                        'Apname' => 'ArubaAP',
                        'S/N' => '--'
                    ]

                ]);
            }
        }
        for ($l = 0; $l < count($ip_db); $l++) {
            $arr_ip[] = $ip_db[$l]['Max'];
        }

        $re = array_diff($mac_ap, $arr_ip);


        for ($x = 0; $x < count($mac_ap); $x++) {

            if ($re[$x] != null) {
                $inser = $add_db->insertMany([
                    [
                        'Max' => $re[$x],
                        'Apname' => 'ArubaAP',
                        'S/N' => '--'
                    ]


                ]);
            }
        }


        $online = array_intersect($arr_ip, $mac_ap);
        $online = array_values($online);
        for ($k = 0; $k < count($online); $k++) {
            $o = $o + 1;

            echo ($online[$k] . " " . $ip_db[$k]['Apname'] . " Online" . " " . $d . "/" . $m . "/" . $Y . " " . $h . ":" . $min  . " " );
        }


        $f = 0;

        $offline = array_diff($arr_ip, $mac_ap);

        $offline = array_values($offline);
        for ($g = 0; $g < count($offline); $g++) {
            $f = $f + 1;
            $inser = $off->insertMany([
                [
                    'Max' => $offline[$g],
                    'Apname' => 'Apname',
                    'Status' => 'Offline',
                    'd/m/y' => $d . "/" . $m . "/" . $Y,
                    'time' => $h . ":" . $min,
                ]

            ]);



            echo ($offline[$g] . " " . $ip_db[$g]['Apname'] . " Offline" . " " . $d . "/" . $m . "/" . $Y . " " . $h . ":" . $min  . " " );
        }
    }
}
