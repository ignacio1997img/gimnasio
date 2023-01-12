<?php

namespace App\Http\Controllers;

use App\Models\Hour;
use Illuminate\Http\Request;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller
{
    public function index()
    {
        $shift = Shift::where('deleted_at',null)->get();

        return view('shift.browse', compact('shift'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            Shift::create([
                'name'=>$request->name,
                'description'=>$request->description,
                'userRegister_id'=>Auth::user()->id
            ]);
            DB::commit();
            return redirect()->route('shifts.index')->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('shifts.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }


    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            Shift::where('id', $request->id)
            ->update([
                'name'=>$request->name,
                'description'=>$request->description,
                'status'=>$request->status
            ]);
            DB::commit();
            return redirect()->route('shifts.index')->with(['message' => 'Actualizado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('shifts.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    //para abrir cada horarios de cada turnos

    public function indexHour($id)
    {
        $shift_id = $id;
        $hour = Hour::where('deleted_at', null)->where('shift_id', $id)->get();
        return view('shift.hour.browse', compact('hour', 'shift_id'));
    }
}
