<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Service;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;
use App\Models\Hour;
use App\Models\HourInstructor;
use App\Models\Instructor;

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
                'status' => $request->status?1:0
            ]);
            DB::commit();
            return redirect()->route('service-plans.index', ['service'=>$request->service_id])->with(['message' => 'Actualizado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('service-plans.index', ['service'=>$request->service_id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }


    //para abrir cada horarios de cada turnos

    public function indexHour($service)
    {
        // return $service;
        $service = Service::where('id', $service)->first();

        $hour = Hour::where('deleted_at', null)->where('service_id', $service->id)->get();
        // return $hour;
        return view('service.hour.browse', compact('hour', 'service'));
    }

    public function storeHour(Request $request)
    {
        DB::beginTransaction();
        try {
            Hour::create([
                'service_id' => $request->service_id,
                'name'=>$request->name,
                'description' => $request->description,
                'userRegister_id' => Auth::user()->id
            ]);
            DB::commit();
            return redirect()->route('service-hour.index', ['service'=>$request->service_id])->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('service-hour.index', ['service'=>$request->service_id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function updateHour(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            Hour::where('id', $request->id)
            ->update([
                'name'=>$request->name,
                'description' => $request->description,
                'status' => $request->status?1:0

            ]);
            DB::commit();
            return redirect()->route('service-hour.index', ['service'=>$request->service_id])->with(['message' => 'Actualizado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('service-hour.index', ['service'=>$request->service_id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function indexInstructor($service, $hour)
    {
        $service = Service::where('id', $service)->first();
        $hour = Hour::where('service_id', $service->id)->where('id', $hour)->first();
        // return $hour;
        $instructor = Instructor::with(['people'])->where('deleted_at', null)->where('status', 1)->get();

        $hourInst = HourInstructor::where('hour_id', $hour->id)->where('deleted_at', null)->get();
        // return $hourInst;
        return view('service.hour.instructor.browse', compact('hour', 'service', 'instructor', 'hourInst'));
    }
    public function storeInstructor(Request $request)
    {
        // return $request;
        $ok = HourInstructor::where('deleted_at', null)->where('hour_id', $request->hour)->where('instructor_id', $request->instructor_id)->first();
        if($ok)
        {
            return redirect()->route('service-hour-instructor.index', ['service'=>$request->service, 'hour'=> $request->hour])->with(['message' => 'El instructor ya se encuentra registrado.', 'alert-type' => 'error']);
        }
        DB::beginTransaction();
        try {
            HourInstructor::create([
                'hour_id'=>$request->hour,
                'instructor_id'=>$request->instructor_id,
                'description'=>$request->description,
                'userRegister_id' => Auth::user()->id
            ]);
            DB::commit();
            return redirect()->route('service-hour-instructor.index', ['service'=>$request->service, 'hour'=> $request->hour])->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('service-hour-instructor.index', ['service'=>$request->service, 'hour'=> $request->hour])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function inactivoInstructor($service, $hour, $id)
    {
        HourInstructor::where('id', $id)->update(['status'=>0]);
        return redirect()->route('service-hour-instructor.index', ['service'=>$service, 'hour'=> $hour])->with(['message' => 'Actualizado exitosamente.', 'alert-type' => 'success']);
    }

    public function activoInstructor($service, $hour, $id)
    {
        HourInstructor::where('id', $id)->update(['status'=>1]);
        return redirect()->route('service-hour-instructor.index', ['service'=>$service, 'hour'=> $hour])->with(['message' => 'Actualizado exitosamente.', 'alert-type' => 'success']);
    }

}
