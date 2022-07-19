<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Provider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class ProviderController extends Controller
{
    public function __construct()
    {
        // $this->middleware('providers.index')->only('index');
        // $this->middleware('permission:rrhh-area.store')->only('store');
    }

    public function index()
    {
        // return 1;

        $user = Auth::user();
        // return $user;
        $query_filtro = 'busine_id = '.$user->busine_id;
        if (Auth::user()->hasRole('admin')) {
            $query_filtro = 1;
        }

        $provider = Provider::where('deleted_at', null)->whereRaw($query_filtro)->get();

        return view('provider.browse', compact('provider'));
    }

    public function store(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            $user = Auth::user();
            // $request->merge(['busine_id'=>$user->busine_id]);
            // $request->merge(['userRegister_id'=>$user->id]);
            Provider::create([
                'busine_id' => $user->busine_id,
                'nit' => $request->nit,
                'name' => $request->name,
                'responsible' => $request->responsible,
                'phone'=> $request->phone,
                // 'image'=> $image,
                'address'=> $request->address

            ]);
            DB::commit();
            return redirect()->route('providers.index')->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('providers.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }
}
