<?php

namespace App\Http\Controllers;

use App\Models\Busine;
use Illuminate\Http\Request;
use App\Models\Cashier;
use App\Models\CashiersDetail;
use App\Models\Vault;
use App\Models\VaultsDetail;
use App\Models\VaultsDetailsCash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\CashiersMovement;

class CashierController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $vault = Vault::where('busine_id', $user->busine_id)->first();
        // return $vault;
        $cashier ='';
        if($vault)
        {
            $cashier = Cashier::where('deleted_at', null)->where('vault_id', $vault->id)->get();
        }
        return view('cashier.browse', compact('cashier', 'vault'));
    }


    public function create()
    {
        $user = Auth::user();
        $vault = Vault::where('busine_id', $user->busine_id)->first();
        return view('cashier.add' , compact('vault'));
    }



    public function store(Request $request)
    {
        // dd($request);
        // return $request;
        $cashier = Cashier::where('user_id', $request->user_id)->where('status', '!=', 'cerrada')->where('deleted_at', NULL)->first();

        if(!$cashier){
            // return $request;
            if($request->amount == null || $request->amount==0)
            {
                return redirect()->route('cashiers.create')->with(['message' => 'Sin monto asignado a la caja.', 'alert-type' => 'warning']);
            }
            // return 1;
            DB::beginTransaction();
            try {
                $cashier = Cashier::create([
                    'vault_id' => $request->vault_id,
                    'user_id' => $request->user_id,
                    'title' => $request->title,
                    'observations' => $request->observations,
                    'status' => 'apertura pendiente'
                ]);

                if($request->amount){
                    CashiersMovement::create([
                        'user_id' => Auth::user()->id,
                        'cashier_id' => $cashier->id,
                        'amount' => $request->amount,
                        'description' => 'Monto de apertura de caja.',
                        'type' => 'ingreso'
                    ]);

                    // Registrar detalle de bóveda
                    $cashier = Cashier::with('user')->where('id', $cashier->id)->first();
                    $detail = VaultsDetail::create([
                        'user_id' => Auth::user()->id,
                        'vault_id' => $request->vault_id,
                        'cashier_id' => $cashier->id,
                        'description' => 'Traspaso a '.$cashier->title,
                        'type' => 'egreso',
                        'status' => 'aprobado'
                    ]);

                    for ($i=0; $i < count($request->cash_value); $i++) { 
                        // if($request->quantity[$i]){
                            VaultsDetailsCash::create([
                                'vaults_detail_id' => $detail->id,
                                'cash_value' => $request->cash_value[$i],
                                'quantity' => $request->quantity[$i],
                            ]);
                        // }
                    }
                }

                DB::commit();
    
                return redirect()->route('cashiers.index')->with(['message' => 'Registro guardado exitosamente.', 'alert-type' => 'success', 'id_cashier_open' => $cashier->id]);
            } catch (\Throwable $th) {
                DB::rollback();
                //throw $th;
                return redirect()->route('cashiers.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
            }
        }else{
            return redirect()->route('cashiers.index')->with(['message' => 'El usuario seleccionado tiene una caja que no ha sido cerrada.', 'alert-type' => 'warning']);
        }
    }


    //para que el cajero acepte el monto de dinero y abilite la caja
    public function change_status($id, Request $request){
        // return 11;
        // DB::beginTransaction();
        try {
            if($request->status == 'abierta'){
                $message = 'Caja aceptada exitosamente.';
                Cashier::where('id', $id)->update([
                    'status' => $request->status
                ]);
            }else{
                $cashier = Cashier::with(['vault_details.cash' => function($q){
                    $q->where('deleted_at', NULL);
                }])->where('id', $id)->first();

                $message = 'Caja rechazada exitosamente.';
                Cashier::where('id', $id)->update([
                    'status' => 'Rechazada',
                    'deleted_at' => Carbon::now()
                ]);

                $vault_detail = VaultsDetail::create([
                    'user_id' => Auth::user()->id,
                    'vault_id' => $cashier->vault_details->vault_id,
                    'cashier_id' => $cashier->id,
                    'description' => 'Rechazo de apertura de caja de '.$cashier->title.'.',
                    'type' => 'ingreso',
                    'status' => 'aprobado'
                ]);
                foreach ($cashier->vault_details->cash as $item) {
                    if($item->quantity > 0){
                        VaultsDetailsCash::create([
                            'vaults_detail_id' => $vault_detail->id,
                            'cash_value' => $item->cash_value,
                            'quantity' => $item->quantity
                        ]);
                    }
                }
            }

            // DB::commit();
            return redirect()->route('voyager.dashboard')->with(['message' => $message, 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            // DB::rollback();
            // dd($th);
            return redirect()->route('voyager.dashboard')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    //para que el cajero cierre la caja y devuekva el dinero
    public function close($id){
        // return 234234;
        $cashier = Cashier::with(['movements' => function($q){
            $q->where('deleted_at', NULL);
        }])
        ->where('id', $id)->where('deleted_at', NULL)->first();
        return view('cashier.close', compact('cashier'));
    }
    
    public function close_store($id, Request $request){
        // dd($request);
        // return $request;
        DB::beginTransaction();
        try {
            $cashier = Cashier::findOrFail($id);
            if($cashier->status != 'cierre pendiente'){
                $cashier->closed_at = Carbon::now();
                $cashier->status = 'cierre pendiente';
                $cashier->save();

                for ($i=0; $i < count($request->cash_value); $i++) { 
                    // if($request->quantity[$i]){
                        CashiersDetail::create([
                            'cashier_id' => $id,
                            'cash_value' => $request->cash_value[$i],
                            'quantity' => $request->quantity[$i],
                        ]);
                    // }
                }
            }

            DB::commit();
            return redirect()->route('voyager.dashboard')->with(['message' => 'Caja cerrada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return redirect()->route('voyager.dashboard')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }







    //para imprimir el comproibante cuando se habre una caja
    public function print_open($id){
        $cashier = Cashier::with(['user', 'vault_details' => function($q){
            $q->where('deleted_at', NULL);
        }, 'vault_details.cash' => function($q){
            $q->where('deleted_at', NULL);
        }, 'movements' => function($q){
            $q->where('deleted_at', NULL);
        }])->where('id', $id)->first();

        $busine = Vault::with('busine')->where('id', $cashier->vault_id)->first();
        
        // dd($cashier);
        // $view = view('cashier.print-open', compact('cashier'));
        return view('cashier.print-open', compact('cashier', 'busine'));
    }




    //para confirmar el cierre de caja 
    public function confirm_close($id)
    {
        $cashier = Cashier::with(['details' => function($q){
            $q->where('deleted_at', NULL);
        }])->where('id', $id)->first();

        // return $cashier;

        
        if($cashier->status == 'cierre pendiente'){
            return view('cashier.confirm_close', compact('cashier'));
        }else{
            // return redirect()->route('voyager.cashiers.index')->with(['message' => 'La caja ya no está abierta.', 'alert-type' => 'warning']);
        }
    }
    public function confirm_close_store($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $cashier = Cashier::findOrFail($id);
            $cashier->status = 'cerrada';
            $cashier->save();
            
            $detail = VaultsDetail::create([
                'user_id' => Auth::user()->id,
                'vault_id' => $request->vault_id,
                'description' => 'Devolución de la caja '.$cashier->title,
                'type' => 'ingreso',
                'status' => 'aprobado'
            ]);

            for ($i=0; $i < count($request->cash_value); $i++) { 
                // if($request->quantity[$i]){
                    VaultsDetailsCash::create([
                        'vaults_detail_id' => $detail->id,
                        'cashier_id' => $id,
                        'cash_value' => $request->cash_value[$i],
                        'quantity' => $request->quantity[$i],
                    ]);
                // }
            }

            DB::commit();
            return redirect()->route('cashiers.index')->with(['message' => 'Caja cerrada exitosamente.', 'alert-type' => 'success', 'id_cashier_close' => $id]);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return redirect()->route('cashiers.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

}
