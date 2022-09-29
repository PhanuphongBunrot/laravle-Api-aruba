<?php

namespace App\Http\Controllers;

use App\Models\Ipmaster;
use App\Models\Ipap;
use Illuminate\Http\Request;
use MongoDB\Client as Mongo;
use DB;

class dropController extends Controller
{
  public function drop()
  {
    //  $t =  Ipap::select('Max')->paginate(15);
    //  return $t;

    $mon = new Mongo;
    $companydb  = $mon->iparuba;

    $delete = $companydb->dropCollection('statustotal');
    echo "Success";
  }
}
