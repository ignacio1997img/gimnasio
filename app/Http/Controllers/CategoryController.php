<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // return $user;
        $query_filtro = 'busine_id = '.$user->busine_id;
        if (Auth::user()->hasRole('admin')) {
            $query_filtro = 1;
        }

        $category = Category::where('deleted_at', null)->whereRaw($query_filtro)->get();


        // return $query_filtro;
        return view('category.browse', compact('category'));
    }

    public function store(Request $request)
    {
        // return $request;
        
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $request->merge(['busine_id'=>$user->busine_id]);
            $request->merge(['userRegister_id'=>$user->id]);
            // return $request;
            Category::create($request->all());
            DB::commit();
            return redirect()->route('categories.index')->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('categories.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }
    
    public function update(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {

            if($request->status)
            {
                $request->merge(['status'=>1]);
                // return 11;
            }
            else
            {
                $request->merge(['status'=>0]);
            }
            // return $request;
            $category = Category::find($request->id);
            $category->update($request->all());

            DB::commit();
            return redirect()->route('categories.index')->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('categories.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }
}
