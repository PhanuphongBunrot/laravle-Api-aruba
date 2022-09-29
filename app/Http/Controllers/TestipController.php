<?php

namespace App\Http\Controllers;
use App\Models\Statustotal;
use App\Models\Ipap;
use App\Models\Product;
use Illuminate\Http\Request;
use MongoDB\Client as Mongo;
use DB;

class TestipController extends Controller
{
 public function test(){
  $q = Statustotal::all();
    
   $t =  Statustotal::paginate(10);
 


  return $t;
  
 }
}