<?php

namespace App\Http\Controllers;

use App\Appoitment;
use App\Doctor;
use Carbon\Carbon;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    public function index()
    {
        $appointments = Appoitment::with(['clinic','doctor','patient','speciality'])->get();
        $doctors = Doctor::all();

        return view('home', compact('appointments','doctors'));
    }

    public function filter(Request $request)
    {
        $date = Carbon::parse($request->date);

        if ($request->doctor_id && $request->date) {
            $appointments = Appoitment::with(['clinic','doctor','patient','speciality'])
                ->whereHas('doctor', function ($q) use ($request) {
                    $q->where('api_id', $request->doctor_id);
                })
                ->where('booked_date', $date)
                ->get();
        } elseif ($request->doctor_id && !$request->date) {
            $appointments = Appoitment::with(['clinic','doctor','patient','speciality'])
                ->whereHas('doctor', function ($q) use ($request) {
                    $q->where('api_id', $request->doctor_id);
                })->get();
        } elseif (!$request->doctor_id && $request->date) {
            $appointments = Appoitment::where('booked_date', $date)->get();
        }
        $doctors = Doctor::all();

        return view('home', compact('appointments','doctors'));
    }
}


