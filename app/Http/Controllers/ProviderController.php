<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Provider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
// use Intervention\Image;

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
        // dd($request);
        DB::beginTransaction();
        try {
            $user = Auth::user();
            // $request->merge(['busine_id'=>$user->busine_id]);
            // $request->merge(['userRegister_id'=>$user->id]);
            $imagen=null;
            if($request->file('image'))
            {
                $imagen = $this->agregar_imagenes($request->file('image'));
            }

            // return "hola";
            Provider::create([
                'busine_id' => $user->busine_id,
                'nit' => $request->nit,
                'name' => $request->name,
                'responsible' => $request->responsible,
                'phone'=> $request->phone,
                'image'=> $imagen,
                'address'=> $request->address

            ]);
            // return 1;
            DB::commit();
            return redirect()->route('providers.index')->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);

        } catch (\Throwable $th) {
            DB::rollBack();
            // return 0;
            return redirect()->route('providers.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function agregar_imagenes($file){
        // return $file;
        Storage::makeDirectory('provider/'.date('F').date('Y'));
        // $base_name = str_random(20);
        $base_name = Str::random(40);

        // return $base_name;
        
        // imagen normal
        $filename = $base_name.'.'.$file->getClientOriginalExtension();
        $image_resize = Image::make($file->getRealPath())->orientate();
        // return $filename;
        $image_resize->resize(1200, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        
        $path =  'provider/'.date('F').date('Y').'/'.$filename;
        $image_resize->save(public_path('../storage/app/public/'.$path));
        $imagen = $path;

        // imagen mediana
        $filename_medium = $base_name.'_medium.'.$file->getClientOriginalExtension();
        $image_resize = Image::make($file->getRealPath())->orientate();
        $image_resize->resize(650, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $path_medium = 'provider/'.date('F').date('Y').'/'.$filename_medium;
        $image_resize->save(public_path('../storage/app/public/'.$path_medium));
        // return 11;


        // imagen pequeña
        $filename_small = $base_name.'_small.'.$file->getClientOriginalExtension();
        $image_resize = Image::make($file->getRealPath())->orientate();
        $image_resize->resize(260, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $path_small = 'provider/'.date('F').date('Y').'/'.$filename_small;
        $image_resize->save(public_path('../storage/app/public/'.$path_small));

        // imagen Recortada
        $filename_cropped = $base_name.'_cropped.'.$file->getClientOriginalExtension();
        $image_resize = Image::make($file->getRealPath())->orientate();
        $image_resize->resize(300, 250, function ($constraint) {
            $constraint->aspectRatio();
        });
        $path_cropped = 'provider/'.date('F').date('Y').'/'.$filename_cropped;
        $image_resize->save(public_path('../storage/app/public/'.$path_cropped));

        return $imagen;
    }
}
