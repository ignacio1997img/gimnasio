<?php

namespace App\Http\Controllers;

use App\Models\People;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $query_filter = 'busine_id = '.$user->busine_id;
        if (Auth::user()->hasRole('admin')) {
            $query_filter = 1;
        }
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
                    ->where('deleted_at', NULL)->whereRaw($query_filter)->orderBy('id', 'DESC')->paginate($paginate);
                    // dd($data->links());
        return view('people.list', compact('data'));
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $people = People::create($request->all());
            DB::commit();
            return response()->json(['people' => $people]);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

   
}
