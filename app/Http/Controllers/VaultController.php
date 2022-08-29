<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Vault;
use App\Models\VaultsDetail;
use App\Models\VaultsDetailsCash;
use App\Models\VaultsClosure;
use App\Models\VaultsClosuresDetail;
use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\Auth;

class VaultController extends Controller
{
    public function index()    
    {
        $busine = Auth::user();
        $vault = Vault::with(['details.cash' => function($q){
                    $q->where('deleted_at', NULL);
                }, 'details' => function($q){
                    $q->where('deleted_at', NULL);
                }])
            ->where('deleted_at', NULL)->where('busine_id', $busine->busine_id)->first();
        
        $auxvault=0;
        // return $vault;
        if($vault)
        {
            $auxvault = 1;
        }
            // return $auxvault;
        $user = Auth::user();
        // return $user;
        // return $vault;
        return view('vault.browse', compact('vault', 'user', 'auxvault'));
    }




    //para crear una nueva boveda
    public function store(Request $request)
    {
        try {
            Vault::create([
                'user_id' => Auth::user()->id,
                'name' => $request->name,
                'description' => $request->description,
                'busine_id' => $request->busine_id,
                'status' => 'activa'
            ]);
            return redirect()->route('vaults.index')->with(['message' => 'Bóveda guardada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('vaults.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }


    //para agregar movimiento a la  Boveda
    public function details_store($id, Request $request){
        // dd($request);
        // return $request;
        DB::beginTransaction();
        try {
            $detail = VaultsDetail::create([
                'user_id' => Auth::user()->id,
                'vault_id' => $id,
                // 'bill_number' => $request->bill_number,
                'name_sender' => $request->name_sender,
                'description' => $request->description,
                'type' => $request->type,
                'status' => 'aprobado'
            ]);

            // return $request;

            for ($i=0; $i < count($request->cash_value); $i++) { 
                // if($request->quantity[$i]){
                    VaultsDetailsCash::create([
                        'vaults_detail_id' => $detail->id,
                        'cash_value' => $request->cash_value[$i],
                        'quantity' => $request->quantity[$i],
                    ]);
                // }
            }
            DB::commit();
            return redirect()->route('vaults.index')->with(['message' => 'Detalle de bóveda guardado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return redirect()->route('vaults.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function open($id, Request $request){
        // return 1111;
        DB::beginTransaction();
        try {

            Vault::where('id', $id)->update([
                'status' => 'activa',
                // 'closed_at' => Carbon::now()
            ]);
            DB::commit();
            return redirect()->route('vaults.index')->with(['message' => 'Bóveda abierta exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return redirect()->route('vaults.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function close($id){
        // dd($id);
        $vault_closure = VaultsClosure::with('details')->where('vault_id', $id)->orderBy('id', 'DESC')->first();
        $date = $vault_closure ? $vault_closure->created_at : NULL;
        // return $vault_closure;
        $vault = Vault::with(['details' => function($q) use($date){
                        if($date){
                            $q->where('created_at', '>', $date);
                        }
                    }, 'details.cash', 'details.cashier.user'])
                    ->where('status', 'activa')->where('id', $id)->where('deleted_at', NULL)->first();
        // dd($vault);
        // return $vault;
        return view('vault.close', compact('vault', 'vault_closure'));
    } 

    public function close_store($id, Request $request){
        // return 1;
        // $cashier_open = Cashier::where('status', 'abierta')->where('deleted_at', NULL)->count();
        // if($cashier_open){
        //     return redirect()->route('vaults.index')->with(['message' => 'No puedes cerrar bóveda porque existe una caja abierta.', 'alert-type' => 'error']);
        // }

        DB::beginTransaction();
        try {

            Vault::where('id', $id)->update([
                'status' => 'cerrada',
                'closed_at' => Carbon::now()
            ]);

            $vault_closure = VaultsClosure::create([
                'vault_id' => $id,
                'user_id' => Auth::user()->id,
                'observations' => $request->observations
            ]);

            for ($i=0; $i < count($request->cash_value); $i++) { 
                VaultsClosuresDetail::create([
                    'vaults_closure_id' => $vault_closure->id,
                    'cash_value' => $request->cash_value[$i],
                    'quantity' => $request->quantity[$i],
                ]);
            }
            DB::commit();
            return redirect()->route('vaults.index')->with(['message' => 'Bóveda cerrada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return redirect()->route('vaults.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function print_status($id){
        $vault = Vault::with(['user', 'details.cash' => function($q){
            $q->where('deleted_at', NULL);
        }, 'details' => function($q){
            $q->where('deleted_at', NULL);
        }])->where('id', $id)->where('deleted_at', NULL)->first();

        // return 2;

        return view('vault.print.print-vaults', compact('vault'));
    }


    public function create()
    {
        //
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
