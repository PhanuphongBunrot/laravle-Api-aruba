<?php

namespace App\Http\Controllers;
use App\Models\Statustotal;
use MongoDB\Client as Mongo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Prophecy\Doubler\Generator\Node\ReturnTypeNode;

class UpdataMBController extends Controller
{
    public function updata()
    {
    
            $search = $_GET['search'];
           
        $q = Statustotal::where('Max','LIKE','%'.$search.'%')->paginate(10);
        return $q;
         

    }
}
