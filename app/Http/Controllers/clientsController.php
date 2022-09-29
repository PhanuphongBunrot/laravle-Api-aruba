<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class clientsController extends Controller
{
    //
    public function clients(){
         
        $ip = array("172.16.0.50", "172.16.4.50", "172.16.8.50", "172.16.12.50", "172.16.16.50");
        for ($i = 0 ; $i <count($ip);$i++){
        $resp = Http::withHeaders([
            'Content-Type' => 'application/json;charset=UTF-8'
            ])
            ->withOptions(["verify"=>false])
            ->post('https://'.$ip[$i].':4343/rest/login', [
            'user' => 'admin',
            'passwd' => 'ssit1234'
            ]);
         $sid = $resp->json()['sid'];
        //echo $sid;
        $response = Http::withHeaders([
            'Content-Type' => 'application/json;charset=UTF-8'
        ])
            ->withOptions(["verify" => false])
            ->get('https://'.$ip[$i].':4343/rest/show-cmd?iap_ip_addr='.$ip[$i].'&cmd=show%20clients&sid='.$sid);
            //echo $response;
             $ex = explode('\n', $response);
            
             for ($x = 9; $x < count($ex)-2; $x++) {
               $keywords = preg_split("/[\s,]+/", $ex[$x]);
            //    echo "<pre>";
            // print_r($keywords);
            // echo "</pre>";
                 if(count($keywords)==17){
                   echo $keywords[0]."/".$keywords[1]."/".$keywords[2]."/".$keywords[3]."/".$keywords[4]." ".$keywords[5]." ".$keywords[6] 
                   ."/".$keywords[7]."/".$keywords[8]."/".$keywords[9]."/".$keywords[10]." ".$keywords[11]." ".$keywords[12]." ".$keywords[13]
                   ."/".$keywords[14]."/".$keywords[15]."/".$keywords[16]."/";   
                 }
                 if(count($keywords)==18){
                    echo $keywords[0]."/".$keywords[1]."/".$keywords[2]."/".$keywords[3]."/".$keywords[4]." ".$keywords[5]." ".$keywords[6]." ".$keywords[7]  
                    ."/".$keywords[8]."/".$keywords[9]."/".$keywords[10]."/".$keywords[11]." ".$keywords[12]." ".$keywords[13]." ".$keywords[14]
                    ."/".$keywords[15]."/".$keywords[16]."/".$keywords[17]."/";   
                  }
                if(count($keywords)==19){
                        echo $keywords[0]."/".$keywords[1]."/".$keywords[2]."/".$keywords[3]." ".$keywords[4]."/".$keywords[5]." ".$keywords[6]." ".$keywords[7]  
                        ." ".$keywords[8]."/".$keywords[9]."/".$keywords[10]."/".$keywords[11]."/ ".$keywords[12]." ".$keywords[13]." ".$keywords[14]
                        ." ".$keywords[15]."/".$keywords[16]."/".$keywords[17]."/".$keywords[18]."/";   
                      }
                        
                    
                
                
                
                
                
                
            //     // echo"<pre>";
            //         // print_r($keywords);
            //         // echo"</pre>";
            //         // if (count($keywords)== 17){ 
            //         //     $json=  new \stdClass();
            //         //     $json->name = "$keywords[0]"; 
            //         //     $json->IPAddress = "$keywords[1]";
            //         //     $json->MACAddress = "$keywords[2]";
            //         //     $json->OS  = "$keywords[3]";
            //         //     $json->ESSID = "$keywords[4]"." "."$keywords[5]"." "."$keywords[6]";
            //         //     $json->AccessPoin = "$keywords[7]";
            //         //     $json->Channel = "$keywords[8]";
            //         //     $json->Type = "$keywords[9]";
            //         //     $json->Role= "$keywords[10]"." "."$keywords[11]"." "."$keywords[12]"." "."$keywords[13]";
            //         //     $json->IPv6Address = "$keywords[14]";
            //         //     $json->Signal = "$keywords[15]";
            //         //     $json->Speed = "$keywords[16]";
            //         //     $myjson = json_encode($json);
            //         //     echo $myjson;
            //         // }
            //         // else if (count($keywords)== 18){
            //         //     $json=  new \stdClass();
            //         //     $json->name = "$keywords[0]"; 
            //         //     $json->IPAddress = "$keywords[1]";
            //         //     $json->MACAddress = "$keywords[2]";
            //         //     $json->OS  = "$keywords[3]";
            //         //     $json->ESSID = "$keywords[4]"." "."$keywords[5]"." "."$keywords[6]"." "."$keywords[7]";
            //         //     $json->AccessPoin = "$keywords[8]";
            //         //     $json->Channel = "$keywords[9]";
            //         //     $json->Type = "$keywords[10]";
            //         //     $json->Role= "$keywords[11]"." "."$keywords[12]"." "."$keywords[13]"." "."$keywords[14]";
            //         //     $json->IPv6Address = "$keywords[15]";
            //         //     $json->Signal = "$keywords[16]";
            //         //     $json->Speed = "$keywords[17]";
            //         //     $myjson = json_encode($json);
            //         //     echo $myjson;
            //         // }
             }
}
    }}
