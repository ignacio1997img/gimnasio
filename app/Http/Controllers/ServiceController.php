<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Service;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\returnSelf;

class ServiceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query_filter = 'busine_id = '.$user->busine_id;
        if (Auth::user()->hasRole('admin')) {
            $query_filter = 1;
        }
        $service = Service::where('deleted_at', null)->whereRaw($query_filter)->get();
        // return $services;
        return view('service.browse', compact('service'));
    }


    public function indexPlan($service)
    {
        $service = Service::where('id', $service)->first();

        $plans = Plan::where('service_id', $service->id)->where('deleted_at', null)->get();
        return view('service.plan.browse', compact('service', 'plans'));
    }

    public function storePlan(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            Plan::create([
                'name' => $request->name,
                'service_id' => $request->service_id,
                'description' => $request->description,
                'day' => $request->day,
                'amount' => $request->amount,
                'userRegister_id' => Auth::user()->id
            ]);
            DB::commit();
            return redirect()->route('service-plans.index', ['service'=>$request->service_id])->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('service-plans.index', ['service'=>$request->service_id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function updatePlan(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            Plan::where('id', $request->id)->update([
                'name' => $request->name,
                'description' => $request->description,
                'day' => $request->day,
                'amount' => $request->amount,
                'status' => $request->status,
            ]);
            DB::commit();
            return redirect()->route('service-plans.index', ['service'=>$request->service_id])->with(['message' => 'Actualizado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('service-plans.index', ['service'=>$request->service_id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }
}
