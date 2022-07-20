<?php

namespace App\Http\Controllers;

use App\Models\People;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeopleController extends Controller
{
    public function index()
    {
        // $user = Auth::user();
        // dd($user);
        return view('people.browse');
    }


    public function list($search = null){
        $user = Auth::user();
        // dd($user);
        $paginate = request('paginate') ?? 10;
        $data = People::where(function($query) use ($search){
                    $query->OrWhereRaw($search ? "id = '$search'" : 1)
                    ->OrWhereRaw($search ? "first_name like '%$search%'" : 1)
                    ->OrWhereRaw($search ? "last_name like '%$search%'" : 1)
                    ->OrWhereRaw($search ? "CONCAT(first_name, ' ', last_name) like '%$search%'" : 1)
                    ->OrWhereRaw($search ? "ci like '%$search%'" : 1)
                    ->OrWhereRaw($search ? "phone like '%$search%'" : 1);
                    })
                    ->where('deleted_at', NULL)->where('busine_id', $user->busine_id)->orderBy('id', 'DESC')->paginate($paginate);
                    // dd($data->links());
        return view('people.list', compact('data'));
    }

   
}
