<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Vault;
use App\Models\VaultsDetail;
use App\Models\VaultsDetailsCash;

use Illuminate\Support\Facades\Auth;

class VaultControlle extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vault = Vault::with(['details.cash' => function($q){
                    $q->where('deleted_at', NULL);
                }, 'details' => function($q){
                    $q->where('deleted_at', NULL);
                }])
            ->where('deleted_at', NULL)->first();

        return view('vault.browse', compact('vault'));
    }




//para crear una nueva boveda
    public function store(Request $request)
    {
        try {
            Vault::create([
                'user_id' => Auth::user()->id,
                'name' => $request->name,
                'description' => $request->description,
                'status' => 'activa'
            ]);
            return redirect()->route('vaults.index')->with(['message' => 'Bóveda guardada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('vaults.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }



    public function details_store($id, Request $request){
        // dd($request);
        return $request;
        DB::beginTransaction();
        try {
            $detail = VaultsDetail::create([
                'user_id' => Auth::user()->id,
                'vault_id' => $id,
                'bill_number' => $request->bill_number,
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
