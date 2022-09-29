<?php

namespace App\Http\Controllers;

use MongoDB\Client as Mongo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UpdataMBController extends Controller
{
    public function updata()
    {


        $conn =  new Mongo;
        $companydb  = $conn->iparuba;
        $updata = $companydb->ipaps;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json;charset=UTF-8'
        ])
            ->withOptions(["verify" => false])
            ->get('http://127.0.0.1:8000/api/apistatus');
       
         
         $ex = explode(" ", $response );

         return $ex ;
        // $updateResult = $updata->replaceOne( 
        //     ['Max' => $max],
        //     ['Max' => , 
        //      'Apname' =>  ,
        //      'S/N'=> 
        //     ]
        //   );
    }
}
