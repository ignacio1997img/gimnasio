<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCG\Voyager\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Models\Busine;
use App\Models\User;

class BusineController extends Controller
{
    public function index()
    {
        $busine = Busine::where('deleted_at', null)->get();
        return view('busine.browse', compact('busine'));
    }

    public function indexUser($id)
    {
        // return $id;
        $role = Role::where('id','>=', 3)->get();
        $busine = $id;
        // return $role;
        $user = User::where('busine_id', $id)->get();
        // dd($user);
        return view('user.browse', compact('busine','role', 'user'));
    }

    public function storeUser(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'busine_id' => $request->busine_id,
                'password' => bcrypt($request->password)
            ]);
            DB::commit();
            return redirect()->route('busines-user.index', $request->busine_id)->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);


        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('busines-user.index', $request->busine_id)->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }


    public function updateUser(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            $user = User::find($request->id);
            $user->update(['name' => $request->name, 'email'=>$request->email,
                            'role_id'=>$request->role_id, 'status'=>$request->status
            ]);
            if($request->password)
            {
                $user->update(['password'=> bcrypt($request->password)]);
            }

            
            DB::commit();
            return redirect()->route('busines-user.index', $user->busine_id)->with(['message' => 'Actualizado exitosamente.', 'alert-type' => 'success']);


        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('busines-user.index', $user->busine_id)->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }
}
