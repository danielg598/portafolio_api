<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Utilities\HTTPHelpers;

class Consultas extends Controller{
    public function getConsulta(){
        $res = DB::table('city')
        ->select('Name as nombre','District as capital','Population as poblacion')
        ->get();

        return HTTPHelpers::responseJson(['resp' => $res]);
    }
}
