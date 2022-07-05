<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Plan;
use App\Models\People;
use App\Models\Cashier;
use App\Models\Service;
use App\Models\Attention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class AttentionController extends Controller
{
    



    // public function planillas_pagos_people_search(){
    //     // $search = \Request::query('q');
    //     // $type = \Request::query('type');
    //     // if($type == 0){
    //     //     $people = DB::connection('mysqlgobe')->table('contribuyente as p')
    //     //                 ->whereRaw($search ? '(PNombre like "%'.$search.'%" || SNombre like "%'.$search.'%" || APaterno like "%'.$search.'%" || AMaterno like "%'.$search.'%" || REPLACE(NombreCompleto, "  ", " ") like "%'.$search.'%" || N_Carnet like "%'.$search.'%")' : 1)
    //     //                 ->selectRaw('p.N_Carnet as id, CONCAT(p.PNombre, " ", p.SNombre) as first_name, CONCAT(p.APaterno, " ", p.AMaterno) as last_name, p.N_Carnet as ci, p.Profesion as profession')
    //     //                 ->groupBy('p.N_Carnet')
    //     //                 ->get();
    //     //     return response()->json($people);
    //     // }else{
    //     //     $people = People::whereRaw($search ? '(first_name like "%'.$search.'%" || last_name like "%'.$search.'%" || CONCAT(first_name, " ", last_name) like "%'.$search.'%" || ci like "%'.$search.'%")' : 1)
    //     //                     ->where('deleted_at', NULL)->get();
    //     // }

    //     // $people = People::whereRaw($search ? '(first_name like "%'.$search.'%" || last_name like "%'.$search.'%" || CONCAT(first_name, " ", last_name) like "%'.$search.'%" || ci like "%'.$search.'%")' : 1)
    //     //     ->where('deleted_at', NULL)->get();
    //         $people = People::all();
    //     return response()->json($people);
    // }

}
