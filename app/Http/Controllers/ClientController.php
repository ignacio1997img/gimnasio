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
use App\Models\Category;
use App\Models\Article;
use App\Models\Wherehouse;
use App\Models\WherehouseDetail;
use App\Models\Item;
use App\Models\Adition;

class ClientController extends Controller
{
    public function index()
    {
        $day = Day::all();
        $service = Service::all();
        $plan = Plan::all();
        $user = Auth::user();
        $people = People::where('deleted_at', null)->where('status', 1)->where('busine_id', $user->busine_id)->get();
        
        $category = Category::where('busine_id', $user->busine_id)->get();

        $article = DB::table('wherehouse_details as wd')
            ->join('wherehouses as w', 'w.id', 'wd.wherehouse_id')
            ->join('articles as a', 'a.id', 'wd.article_id')
            ->where('w.busine_id', $user->busine_id)
            ->where('wd.item', '>', 0)
            ->where('wd.deleted_at', null)
            ->select('a.name', 'a.presentation', 'wd.id', 'wd.itemEarnings', DB::raw("SUM(wd.item) as cant"))
            ->groupBy('wd.article_id', 'wd.itemEarnings')->get();

        // return $article;
        
        $cashier = Cashier::where('user_id', Auth::user()->id)->where('status', 'abierta')->first();
        // return $cashier;
        
        $client = Client::whereHas('cashier.vault.busine', function($q)use($user){
            $q->where('id', $user->busine_id);
        })
        ->where('deleted_at', null)->get();

        $client = Client::whereHas('cashier.vault.busine', function($q)use($user){
            $q->where('id', $user->busine_id);
        })
        ->where('deleted_at', null)->get();

        // return $client;



        // $date = date('Y-m-d'); 
        // $cli = Client::where('status', 1)->where('deleted_at', null)->get();
        // // return $cli;
        // foreach($cli as $item)
        // {
            

        //     if($item->start && $item->finish)
        //     {
        //         // $fin=Carbon::parse($item->finish);    
        //         $fin=Carbon::parse($date);
        //         $inicio=Carbon::parse($item->finish);
        //         if( $fin > $inicio)
        //         {
        //             Client::where('id', $item->id)->update(['status'=>0]);
        //         }
        //     }
        //     else
        //     {
                       
        //         $fin=Carbon::parse($date);
        //         $inicio = date_format($item->created_at, "Y-m-d");        
        //         $inicio=Carbon::parse($inicio);
        //         if( $fin > $inicio)
        //         {
        //             Client::where('id', $item->id)->update(['status'=>0]);
        //         }
        //     }
        // }



     
        return view('client.browse', compact('day', 'service', 'plan', 'people', 'cashier', 'client', 'article', 'category'));
    }

    public function store(Request $request)
    {   
        // return $request;
        DB::beginTransaction();
        try {
            $user = Auth::user()->id;
            $client = Client::create([
                'cashier_id' => $request->cashier_id,
                'service_id' => $request->service_id,
                'plan_id' => $request->plan_id,
                'day_id' => $request->day_id?$request->day_id:null,
                'people_id' => $request->people_id,
                'start' => $request->start? $request->start: null,
                'finish' => $request->finish?$request->finish:null,
                'userRegister_id' => $user,
                'subAmount' => $request->credit? $request->subAmount:$request->amount,
                'amount' => $request->amount,
                'hour' => $request->hour,
                'credit' => $request->credit? '1':'0'
            ]);
            // return 1;
            // return $client;
            Adition::create([
                'client_id' => $client->id,
                'cashier_id' => $request->cashier_id,
                'cant' => $request->credit? $request->subAmount:$request->amount,
                'observation' => 'Pago al momento del servicio',
                'type'=> 'servicio',
                'userRegister_id' => $user           
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


    public function articleStore(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            $user = Auth::user()->id;
            $client = Client::create([
                        'cashier_id' => $request->cashier_id,
                        'people_id' => $request->people_id,
                        'userRegister_id' => $user,
                        'amount' => $request->amount,
                        'subAmount' => $request->credits? $request->subAmount:$request->amount,
                        'credit' => $request->credits? '1':'0'
                    ]);
            $client_id = $client->id;
            $pagar =0;
            for ($i=0; $i < count($request->wherehouseDetail_id); $i++)
            {
                $wherehouse = WherehouseDetail::find($request->wherehouseDetail_id[$i]);
                // return $wherehouse;
                $cant=0;
                $ok=false;
                if($request->cant_stock[$i] <= $wherehouse->item)
                {
                    $wherehouse->decrement('item', $request->cant_stock[$i]);

                    Item::create([
                        'wherehouseDetail_id' => $wherehouse->id,
                        'item' => $request->cant_stock[$i],
                        'itemEarnings' => $wherehouse->itemEarnings,
                        'amount' => $request->total_pagar[$i],
                        'client_id' => $client_id,
                        'indice' => $i
                    ]);
                    $pagar+= $request->total_pagar[$i];
                }
                else
                {
                    $cant = $request->cant_stock[$i];
                    while($cant > 0)
                    {
                        $des=0;
                        $aux = WherehouseDetail::where('article_id', $wherehouse->article_id)->where('itemEarnings', $wherehouse->itemEarnings)
                                ->where('item', '>', 0)
                                ->where('deleted_at', null)
                                ->orderBy('id', 'ASC')->first();
                        $des = $cant > $aux->item ? $aux->item : $cant;
                        
                        $aux->decrement('item', $des);
                        $cant-= $des;
                        
                        Item::create([
                            'wherehouseDetail_id' => $aux->id,
                            'item' => $des,
                            'itemEarnings' => $aux->itemEarnings,
                            'amount' => $des * $aux->itemEarnings,
                            'client_id' => $client_id,
                            'indice' => $i

                        ]);
                        $pagar = $pagar + ($des * $aux->itemEarnings);
                    }
                }
                        
                $client->update(['amount'=>$pagar]);
                
            }
            Adition::create([
                'client_id' => $client_id,
                'cashier_id' => $request->cashier_id,
                'cant' => $request->credits? $request->subAmount:$request->amount,
                'observation' => 'Pago al momento del servicio',
                'type'=> 'producto',
                'userRegister_id' => $user
            ]);
                DB::commit();
            return redirect()->route('clients.index')->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('clients.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }


    public function aditionStore(Request $request)
    {
        DB::beginTransaction();
        try {
            $ok = Client::find($request->client_id);
            if($ok->amount < ($request->subAmount + $ok->subAmount))
            {
                return redirect()->route('clients.index')->with(['message' => 'El monto ingresado supera a la cantidad de la deuda.', 'alert-type' => 'warning']);
            }
            Adition::create([
                'client_id' => $ok->id,
                'cashier_id' => $request->cashier_id,
                'cant' => $request->subAmount,
                'observation' => $request->observation,
                'type' => $ok->service_id? 'servicio':'producto',
                'userRegister_id' => Auth::user()->id

            ]);
            // return 1;
            $ok->update(['subAmount' => ($ok->subAmount+$request->subAmount) ]);
            DB::commit();
            return redirect()->route('clients.index')->with(['message' => 'Pago registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            // return 0;
            return redirect()->route('clients.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }














    public function ajaxArticle($id)
    {
        return Article::where('category_id', $id)->where('deleted_at', null)->where('status', 1)->get();
    }

    //para ver los plan de pago de una atencion en el modal
    public function ajaxItem($id)
    {
        return DB::table('items as i')
            ->join('wherehouse_details as w', 'w.id', 'i.wherehouseDetail_id')
            ->join('articles as a', 'a.id', 'w.article_id')
            ->where('i.client_id', $id)->where('i.deleted_at', null)
            ->select('a.name','a.presentation', 'i.itemEarnings as price', DB::raw("SUM(i.item) as cant"), DB::raw("SUM(i.amount) as money"))
            ->groupBy('i.indice')->get();
    }

    public function ajaxAdition($id)
    {
        return Adition::where('client_id', $id)->get();
    }

    public function clientBaja()
    {
        
        $date = date('Y-m-d'); 
        $cli = Client::where('status', 1)->where('amount', '=', 'subAmount')->where('deleted_at', null)->get();
        // return $cli;
        foreach($cli as $item)
        {
            

            if($item->start && $item->finish)
            {
                // $fin=Carbon::parse($item->finish);    
                $fin=Carbon::parse($date);
                $inicio=Carbon::parse($item->finish);
                if( $fin > $inicio)
                {
                    Client::where('id', $item->id)->update(['status'=>0]);
                }
            }
            else
            {
                       
                $fin=Carbon::parse($date);
                $inicio = date_format($item->created_at, "Y-m-d");        
                $inicio=Carbon::parse($inicio);
                if( $fin > $inicio)
                {
                    Client::where('id', $item->id)->update(['status'=>0]);
                }
            }
        }
        return 1;
    }
}
