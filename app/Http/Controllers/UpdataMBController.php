<?php

namespace App\Http\Controllers;
use App\Models\Statustotal;
use MongoDB\Client as Mongo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UpdataMBController extends Controller
{
    public function updata()
    {
      
        $search = "172.16.0";

        $q = Statustotal::where('ip','LIKE','%'.$search.'%')->get();
       return $q  ;

    }
}
