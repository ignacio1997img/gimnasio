<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Article;
use App\Models\Busine;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\Return_;

class ArticleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // $busine_id = $user->busine_id;
        // return $user;
        $query_filtro = 'busine_id = '.$user->busine_id;
        $query_article = 'id = '.$user->busine_id;
        if (Auth::user()->hasRole('admin')) {
            $query_filtro = 1;
            $query_article = 1;
        }

        $category = Category::where('deleted_at', null)->where('status', 1)->whereRaw($query_filtro)->get();

        $article = Article::with(['category.busine' => function($q)use($query_article){
                    $q->whereRaw($query_article);
                }])
                ->where('deleted_at', null)->get();
        // return $query_filtro;
        // dd($article);
        return view('article.browse', compact('category', 'article'));
    }
    
    public function store(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {

            $user = Auth::user();
            $busine = Busine::find($user->id);
            $image='';

            $file = $request->file('image');
            if($file)
            {
                $nombre_origen = $file->getClientOriginalName();
                    
                $newFileName = Str::random(20).time().'.'.$file->getClientOriginalExtension();
                // return $newFileName;
                    
                $dir =  "Article/".$busine->id."-".$busine->name."/".date('F').date('Y');
                    
                Storage::makeDirectory($dir);
                Storage::disk('public')->put($dir.'/'.$newFileName, file_get_contents($file));
                $image = $dir."/".$newFileName;

                    
                    // $ok =DonacionArchivo::create([
                    //     'entrada'               => 1,
                    //     'nombre_origen'         => $nombre_origen,
                    //     'donacioningreso_id'    => $request->id,
                    //     'ruta'                  => $dir.'/'.$newFileName,
                    //     'user_id'               => $user->id
                    // ]);

            }
            Article::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'presentation' => $request->presentation,
                'image' => $image
            ]);
            DB::commit();
            return redirect()->route('articles.index')->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('articles.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            if($request->status)
            {
                $request->merge(['status'=>1]);
                // return 11;
            }
            else
            {
                $request->merge(['status'=>0]);
            }
            $article = Article::find($request->id);
            $article->update(['name'=>$request->name, 'category_id'=>$request->category_id, 'presentation'=>$request->presentation, 'status'=>$request->status]);
            DB::commit();
            return redirect()->route('articles.index')->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('articles.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }
}
