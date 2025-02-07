<?php

namespace App\Http\Controllers;

use App\Models\TrainingSchedule;
use Illuminate\Http\Request;

class TrainingScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Admin can see all training schedules, users can only see their own
        if(auth()->user() &&  auth()->user()->role == "admin") {
            return TrainingSchedule::all();
        } else {
            return TrainingSchedule::where('user_id', auth()->user()->id)->get();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Admin can create training schedules for all users, users can only create their own
        if(auth()->user() &&  auth()->user()->role == "admin") {
            return TrainingSchedule::create($request->all());
        } else {
            $request->validate([
                'user_id' => 'required',
                'title' => 'required',
                'description' => 'required',
            ]);
            $request['user_id'] = auth()->user()->id;
            return TrainingSchedule::create($request->all());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TrainingSchedule $trainingSchedule)
    {
        //Admin can see all training schedules, users can only see their own
        if(auth()->user() &&  auth()->user()->role == "admin") {
            return $trainingSchedule;
        } else {
            if($trainingSchedule->user_id == auth()->user()->id) {
                return $trainingSchedule;
            } else {
                return response()->json(['error' => 'Forbidden'], 403);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TrainingSchedule $trainingSchedule)
    {
        //Admin can update all training schedules, users can only update their own
        if(auth()->user() &&  auth()->user()->role == "admin") {
            $trainingSchedule->update($request->all());
            return $trainingSchedule;
        } else {
            if($trainingSchedule->user_id == auth()->user()->id) {
                $trainingSchedule->update($request->all());
                return $trainingSchedule;
            } else {
                return response()->json(['error' => 'Forbidden'], 403);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TrainingSchedule $trainingSchedule)
    {
        //Admin can delete all training schedules, users can only delete their own
        if(auth()->user() &&  auth()->user()->role == "admin") {
            $trainingSchedule->delete();
            return response()->json(['success' => 'Training schedule deleted'], 200);
        } else {
            if($trainingSchedule->user_id == auth()->user()->id) {
                $trainingSchedule->delete();
                return response()->json(['success' => 'Training schedule deleted'], 200);
            } else {
                return response()->json(['error' => 'Forbidden'], 403);
            }
        }
    }
}
