<?php

namespace App\Http\Controllers;
// use App\Models\Day;
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
use App\Models\Hour;
use App\Models\HourInstructor;
use App\Models\User;
use DateTime;

class ClientController extends Controller
{
    public function index()
    {

        // return Plan::where('deleted_at', null)->where('status', 1)->get();

        // $day = Day::all();
        $service = Service::all();
        $plan = Plan::all();

        $user = Auth::user();
        $query_filter= 'busine_id = '.$user->busine_id;
        if (Auth::user()->hasRole('admin'))
        {
            $query_filter =1;
        }
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

        

        $cashier = Cashier::where('user_id', Auth::user()->id)->where('status', 'abierta')->first();
        // return $cashier;


        $client = Client::with(['service', 'plan', 'item.wherehouseDetail.article'])
                ->where('deleted_at', null)
                ->whereRaw($query_filter)
                ->get();

        // return $client;
        
        


     
        return view('client.browse', compact('service', 'plan', 'people', 'cashier', 'client', 'article', 'category'));
    }

    public function list($type, $search = null)
    {
        $cashier = Cashier::where('user_id', Auth::user()->id)->where('status', 'abierta')->first();

        $user = Auth::user();
        $query_filter= 'busine_id = '.$user->busine_id;
        if (Auth::user()->hasRole('admin'))
        {
            $query_filter =1;
        }
        $paginate = request('paginate') ?? 10;

        switch($type)
        {
            case 'todo':
                $data = Client::with(['people', 'service', 'plan', 'item.wherehouseDetail.article'])
                ->where(function($query) use ($search){
                    if($search){
                        $query->OrwhereHas('people', function($query) use($search){
                            $query->whereRaw("(first_name like '%$search%' or last_name like '%$search%' or ci like '%$search%' or CONCAT(first_name, ' ', last_name) like '%$search%')");
                        });
                    }
                })
                ->where('deleted_at', null)
                ->whereRaw($query_filter)
                ->orderBy('id', 'DESC')->paginate($paginate);
                break;
            case 'enpago':
                $data = Client::with(['service', 'plan', 'item.wherehouseDetail.article'])
                ->where(function($query) use ($search){
                    if($search){
                        $query->OrwhereHas('people', function($query) use($search){
                            $query->whereRaw("(first_name like '%$search%' or last_name like '%$search%' or ci like '%$search%' or CONCAT(first_name, ' ', last_name) like '%$search%')");
                        });
                    }
                })
                ->where('deleted_at', null)
                ->whereRaw('subAmount != 0')
                ->whereRaw($query_filter)
                ->orderBy('id', 'DESC')->paginate($paginate);
                break;
            case 'enpagoS':
                $data = Client::with(['service', 'plan', 'item.wherehouseDetail.article'])
                ->where(function($query) use ($search){
                    if($search){
                        $query->OrwhereHas('people', function($query) use($search){
                            $query->whereRaw("(first_name like '%$search%' or last_name like '%$search%' or ci like '%$search%' or CONCAT(first_name, ' ', last_name) like '%$search%')");
                        });
                    }
                })
                ->where('deleted_at', null)
                ->where('type', 'servicio')
                ->whereRaw('subAmount != 0')
                ->whereRaw($query_filter)
                ->orderBy('id', 'DESC')->paginate($paginate);
                break;
            case 'enpagoP':
                $data = Client::with(['service', 'plan', 'item.wherehouseDetail.article'])
                ->where(function($query) use ($search){
                    if($search){
                        $query->OrwhereHas('people', function($query) use($search){
                            $query->whereRaw("(first_name like '%$search%' or last_name like '%$search%' or ci like '%$search%' or CONCAT(first_name, ' ', last_name) like '%$search%')");
                        });
                    }
                })
                ->where('deleted_at', null)
                ->where('type', 'producto')
                ->whereRaw('subAmount != 0')
                ->whereRaw($query_filter)
                ->orderBy('id', 'DESC')->paginate($paginate);
                break;
            case 'pagado':
                $data = Client::with(['service', 'plan', 'item.wherehouseDetail.article'])
                ->where(function($query) use ($search){
                    if($search){
                        $query->OrwhereHas('people', function($query) use($search){
                            $query->whereRaw("(first_name like '%$search%' or last_name like '%$search%' or ci like '%$search%' or CONCAT(first_name, ' ', last_name) like '%$search%')");
                        });
                    }
                })
                ->where('deleted_at', null)
                ->whereRaw('subAmount = 0')
                ->whereRaw($query_filter)
                ->orderBy('id', 'DESC')->paginate($paginate);
                break;
                
            case 'eliminados':
                $data = Client::with(['service', 'plan', 'item.wherehouseDetail.article'])
                ->where(function($query) use ($search){
                    if($search){
                        $query->OrwhereHas('people', function($query) use($search){
                            $query->whereRaw("(first_name like '%$search%' or last_name like '%$search%' or ci like '%$search%' or CONCAT(first_name, ' ', last_name) like '%$search%')");
                        });
                    }
                })
                ->whereNotNull('deleted_at')
                ->whereRaw($query_filter)
                ->orderBy('id', 'DESC')->paginate($paginate);
                break;
        }
    
        $type = $type;

        
        // dd($client);
        return view('client.list', compact('data', 'cashier', 'type'));
    }

    public function store(Request $request)
    {   
        // return $request;

        $cashier = Cashier::where('user_id', Auth::user()->id)->where('status', 'abierta')->first();

        if(!$request->cashier_id || !$cashier)
        {
            return redirect()->route('clients.index')->with(['message' => 'Ups...', 'alert-type' => 'error']);
        }
        if($request->cashier_id != $cashier->id)
        {
            $request->merge(['cashier_id'=> $cashier->id]);
        }



        if($request->subAmount == NULL)
        {
            $request->merge(['subAmount'=>0]);
        }
        if($request->subAmount > $request->amount)
        {
            return redirect()->route('clients.index')->with(['message' => 'El cuota no debe ser mayor al monto total.', 'alert-type' => 'warning']);
        }

        //para ver si tiene fecha inicio y fin
        DB::beginTransaction();
        if(!$request->day)
        {
            $fechaActual = Carbon::parse($request->start);

            $fechaVigencia = Carbon::parse($request->finish);

            $day = $fechaVigencia->diffInDays($fechaActual);
            $request->merge(['day'=>$day+1]);
            // return 1;
        }
        else
        {
            $fechaActual = Carbon::createFromFormat('Y-m-d', $request->start);
            $fechaActual = $fechaActual->addDay($request->day-1);
            $request->merge(['finish'=>$fechaActual]);   
            // return $request;         
        }
        try {
            $user = Auth::user()->id;
            // return $request;

            $aux= $request->amount - ($request->credit? $request->subAmount: $request->amount);
            // return $aux;

            // if($request->subAmount > $request )
            $client = Client::create([
                'busine_id' => Auth::user()->busine_id,
                'cashier_id' => $request->cashier_id,
                'type'=> $request->type,
                'service_id' => $request->service_id,
                'plan_id' => $request->plan_id,
                'people_id' => $request->people_id,
                'start' => $request->start? $request->start: null,
                'finish' => $request->finish?$request->finish:null,
                'userRegister_id' => $user,
                'subAmount' => $request->credit? ($request->amount - $request->subAmount):0,
                'amount' => $request->amount,
                'hour_id' => $request->hour_id,
                'hourInstructor_id' =>$request->instructor_id,
                'credit' => $request->credit? '1':'0',
                'day'=>$request->day,
                'status'=>$aux==0? 'pagado' :'pendiente',
            ]);

            Adition::create([
                'client_id' => $client->id,
                'cashier_id' => $request->cashier_id,
                'cant' => $request->credit? $request->subAmount:$request->amount,
                'observation' => 'Pago al momento del servicio',
                'type'=> $request->type,
                'userRegister_id' => $user           
            ]);
            DB::commit();
            return redirect()->route('clients.index')->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return 0;
            return redirect()->route('clients.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function update(Request $request)
    {   
        return $request;
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
        DB::beginTransaction();
        try {

            $client = Client::where('id', $id)->where('deleted_at', null)->first();
            if($client)
            {
                if(!$client->service_id && !$client->plan_id)
                {
                    // return 1;
                    // return $client;
                    // $adition = Adition::where('client_id', $client->id)
                    $items = Item::where('client_id', $client->id)->where('deleted_at', null)->get();

                    foreach($items as $item)
                    {
                        WherehouseDetail::where('id', $item->wherehouseDetail_id)->increment('item', $item->item);
                    }
                    Adition::where('client_id', $client->id)->where('deleted_at', null)->update(['deleted_at' => Carbon::now(), 'userDelete_id'=>Auth::user()->id]);
                    Item::where('client_id', $client->id)->where('deleted_at', null)->update(['deleted_at' => Carbon::now(), 'userDelete_id'=>Auth::user()->id]);
                    $client->update(['deleted_at' => Carbon::now(), 'userDelete_id'=>Auth::user()->id]);
                }
                else
                {   
                    $client->update(['deleted_at' => Carbon::now(), 'userDelete_id'=>Auth::user()->id]);
                    Adition::where('client_id', $client->id)->update(['deleted_at' => Carbon::now(), 'userDelete_id'=>Auth::user()->id]);
                }
            }
            // $client->update(['deleted_at' => Carbon::now()]);

            
            DB::commit();
            return redirect()->route('clients.index')->with(['message' => 'Anulado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('clients.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
            // return response()->json(['error' => 'Ocurrió un error.']);
        }
    }


    public function articleStore(Request $request)
    {
        // return $request;
        $cashier = Cashier::where('user_id', Auth::user()->id)->where('status', 'abierta')->first();

        if(!$request->cashier_id || !$cashier)
        {
            return redirect()->route('clients.index')->with(['message' => 'Ups...', 'alert-type' => 'error']);
        }
        if($request->cashier_id != $cashier->id)
        {
            $request->merge(['cashier_id'=> $cashier->id]);
        }


        if(!$request->total_pagar)
        {
            return redirect()->route('clients.index')->with(['message' => 'Introduzca algun producto o articulo.', 'alert-type' => 'warning']);
        }


        if($request->subAmount == NULL)
        {
            $request->merge(['subAmount'=>0]);
        }
        $amount =0;
        for ($i=0; $i < count($request->total_pagar); $i++) { 
            $amount+= $request->total_pagar[$i];
        }

        if($request->subAmount > $amount)
        {
            return redirect()->route('clients.index')->with(['message' => 'El cuota no debe ser mayor al monto total.', 'alert-type' => 'warning']);
        }

        // return $request;
        DB::beginTransaction();
        try {
            $user = Auth::user()->id;
            $client = Client::create([
                        'busine_id' => Auth::user()->busine_id,
                        'cashier_id' => $request->cashier_id,
                        'people_id' => $request->people_id,
                        'userRegister_id' => $user,
                        'type'=>'producto',
                        'amount' => $request->amount,
                        'start'=> date('Y-m-d'),
                        'finish'=> date('Y-m-d'),
                        'subAmount' => $request->credits? ($amount - $request->subAmount):$amount,
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
                        'indice' => $i,
                        'userRegister_id' => $user,

                    ]);
                    $pagar+= $request->total_pagar[$i];
                }
                else
                {
                    // para que pueda sacar productos de varios registro pero del mismo item y del mismo precio
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
                            'indice' => $i,
                            'userRegister_id' => $user,


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
            // return 'si';
                DB::commit();
            return redirect()->route('clients.index')->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('clients.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }


    public function aditionStore(Request $request)
    {
        // return $request;
        $cashier = Cashier::where('user_id', Auth::user()->id)->where('status', 'abierta')->first();

        if(!$request->cashier_id || !$cashier)
        {
            return redirect()->route('clients.index')->with(['message' => 'Ups...', 'alert-type' => 'error']);
        }
        if($request->cashier_id != $cashier->id)
        {
            $request->merge(['cashier_id'=> $cashier->id]);
        }


        DB::beginTransaction();
        try {
            $ok = Client::find($request->client_id);
            if(($ok->subAmount - $request->subAmount) < 0)
            {
                // return 0;
                return redirect()->route('clients.index')->with(['message' => 'El monto ingresado supera a la cantidad de la deuda.', 'alert-type' => 'warning']);
            }
            // return 1;
            Adition::create([
                'client_id' => $ok->id,
                'cashier_id' => $request->cashier_id,
                'cant' => $request->subAmount,
                'observation' => $request->observation,
                'type' => $ok->service_id? 'servicio':'producto',
                'userRegister_id' => Auth::user()->id

            ]);
            // return 1;
            $ok->update(['subAmount' => ($ok->subAmount-$request->subAmount) ]);
            if($ok->subAmount == 0)
            {
                $ok->update(['status' => 'pagado']);
            }
            DB::commit();
            return redirect()->route('clients.index')->with(['message' => 'Pago registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            // return 0;
            return redirect()->route('clients.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }



    //***************************************** AJAX ******************************************************* */

// para seleccionar plan de cada servicio elejido

    public function ajaxPlan($id)
    {
        // return User::all();
        return Plan::where('service_id', $id)->where('deleted_at', null)->where('status', 1)->get();
    }
    //para obtener la infomacuion de cada plan-service
    public function ajaxInfPlan($id)
    {
        return Plan::where('id', $id)->first();
    }

    //para selecionar el horario de cada servicio
    public function ajaxHour($id)
    {
        return Hour::where('deleted_at', null)->where('status',1)->where('service_id', $id)->get();
    }

    public function ajaxInstructor($id)
    {
        return HourInstructor::with(['instructor.people'])
        ->where('hour_id', $id)->get();
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
