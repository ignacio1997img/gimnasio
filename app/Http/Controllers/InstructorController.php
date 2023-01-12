<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instructor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\People;
use Swift;

class InstructorController extends Controller
{
    public function index()
    {
        $people = People::where('deleted_at',null)->where('status', 1)->get();
        return view('instructor.browse', compact('people'));
    }

    public function list($type, $search = null)
    {
        $paginate = request('paginate') ?? 10;

        switch($type)
        {
            case 'activo':
                $data = Instructor::with(['people', 'user'])
                    ->where(function($query) use ($search){
                        if($search){
                            $query->OrwhereHas('people', function($query) use($search){
                                $query->whereRaw("(first_name like '%$search%' or last_name like '%$search%' or CONCAT(first_name, ' ', last_name) like '%$search%')");
                            });
                        }
                    })
                    ->where('deleted_at', NULL)->where('status', 1)->orderBy('id', 'DESC')->paginate($paginate);
                    break;
            case 'inactivo':
                $data = Instructor::with(['people', 'user'])
                    ->where(function($query) use ($search){
                        if($search){
                            $query->OrwhereHas('people', function($query) use($search){
                                $query->whereRaw("(first_name like '%$search%' or last_name like '%$search%' or CONCAT(first_name, ' ', last_name) like '%$search%')");
                            });
                        }
                    })
                    ->where('deleted_at', NULL)->where('status', 0)->orderBy('id', 'DESC')->paginate($paginate);
                    break;
        }

        return view('instructor.list', compact('data'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            Instructor::create([
                'people_id'=>$request->people_id,
                'description'=>$request->description,
                'userRegister_id'=>Auth::user()->id
            ]);
            DB::commit();
            return redirect()->route('instructors.index')->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('instructors.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }
}
