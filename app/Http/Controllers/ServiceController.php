<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query_filter = 'busine_id = '.$user->busine_id;
        if (Auth::user()->hasRole('admin')) {
            $query_filter = 1;
        }
        $service = Service::where('deleted_at', null)->whereRaw($query_filter)->get();
        // return $services;
        return view('service.browse', compact('service'));
    }
}
