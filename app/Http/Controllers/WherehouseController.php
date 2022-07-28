<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provider;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Models\Article;
use App\Models\Wherehouse;
use App\Models\WherehouseDetail;
use Carbon\Carbon;

class WherehouseController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $detail = WherehouseDetail::with(['wherehouse' => function($q)use($user){
                    $q->where('busine_id', $user->busine_id);
                }])
                ->where('deleted_at', null)
                ->where('status', 1)->get();

        return view('wherehouse.browse', compact('detail'));
    }

    public function create()
    {
        // return 1;
        $user = Auth::user();
        $category = Category::where('busine_id', $user->busine_id)->get();
        $provider = Provider::where('busine_id', $user->busine_id)->get();

        return view('wherehouse.add', compact('provider', 'category'));
    }

    public function store(Request $request)
    {
        // return $request;  
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $wherehouse = Wherehouse::create([
                            'busine_id' => $user->busine_id,
                            'provider_id' => $request->provider_id,
                            'number' => $request->nrofactura,
                            'userRegister_id' => $user->id
                        ]);
            // return $request;
                    
            for ($i=0; $i < count($request->article_id); $i++) {

                // $aux = WherehouseDetail::find('id',$request->wherehouseDetail_id[$i]);
                
                WherehouseDetail::create([
                    'wherehouse_id' => $wherehouse->id,
                    'article_id' => $request->article_id[$i],
                    'amount' => $request->precio_compra[$i],
                    'items' => $request->cantidad_item[$i],
                    'item' => $request->cantidad_item[$i],
                    'unitPrice' => $request->precio_mayoritario[$i],
                    'itemEarnings' => $request->ganacia_unitaria[$i],
                    'userRegister_id' => $user->id
                ]);
            }
            // return 1;
            DB::commit();
            
            return redirect()->route('wherehouses.index')->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            // return 0;
            return redirect()->route('wherehouses.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);

        }
    }

    public function destroy($id)
    {
        // return $id;
        WherehouseDetail::where('id', $id)->update(['deleted_at'=> Carbon::now()]);
        return redirect()->route('wherehouses.index')->with(['message' => 'Eliminado exitosamente.', 'alert-type' => 'success']);

    }


    public function prueba()
    {
        return 1;
    }

    public function show()
    {
        return 2;
        $user = Auth::user();
        
    }








    public function ajaxArticle($id)
    {
        return Article::where('category_id', $id)->where('deleted_at', null)->where('status', 1)->get();
    }
}
