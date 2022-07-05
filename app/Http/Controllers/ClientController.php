<?php

namespace App\Http\Controllers;
use App\Models\Day;
use App\Models\Plan;
use App\Models\People;
use App\Models\Cashier;
use App\Models\Service;
use Illuminate\Support\Carbon;
// use App\Models\Attention;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $day = Day::all();
        $service = Service::all();
        $plan = Plan::all();
        $people = People::where('deleted_at', null)->where('status', 1)->get();

        $cashier = Cashier::where('user_id', Auth::user()->id)->where('status', 'abierta')->first();
        // return $cashier;

        $client = Client::where('deleted_at', null)->get();

        return view('client.browse', compact('day', 'service', 'plan', 'people', 'cashier', 'client'));
    }

    public function store(Request $request)
    {   
        // return $request;
        DB::beginTransaction();
        try {
            Client::create([
                'cashier_id' => $request->cashier_id,
                'service_id' => $request->service_id,
                'plan_id' => $request->plan_id,
                'day_id' => $request->day_id?$request->day_id:null,
                'people_id' => $request->people_id,
                'start' => $request->start? $request->start: null,
                'finish' => $request->finish?$request->finish:null,
                'userRegister_id' => Auth::user()->id,
                'amount' => $request->amount,
                'hour' => $request->hour
            ]);
            DB::commit();
            return redirect()->route('clients.index')->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            // return 0;
            return redirect()->route('clients.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function update(Request $request)
    {   
        // return $request;
        DB::beginTransaction();
        try {
            
            Client::where('id', $request->id)->update([
                'service_id' => $request->service_id,
                'plan_id' => $request->plan_id,
                'day_id' => $request->day_id?$request->day_id:null,
                'people_id' => $request->people_id,
                'start' => $request->start? $request->start: null,
                'finish' => $request->finish?$request->finish:null,
                'amount' => $request->amount,
                'hour' => $request->hour
            ]);
            DB::commit();
            return redirect()->route('clients.index')->with(['message' => 'Actualizado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            // return 0;
            return redirect()->route('clients.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function destroy($id)
    {
        // return $id;
        try {
            Client::where('id', $id)->update([
                'deleted_at' => Carbon::now()
            ]);
            return redirect()->route('clients.index')->with(['message' => 'Anulado exitosamente.', 'alert-type' => 'success']);

            // return response()->json(['message' => 'Anulado exitosamente.']);
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('clients.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
            // return response()->json(['error' => 'Ocurrió un error.']);
        }
    }
}
