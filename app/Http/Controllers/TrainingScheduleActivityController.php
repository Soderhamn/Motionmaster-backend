<?php

namespace App\Http\Controllers;

use App\Models\TrainingScheduleActivity;
use Illuminate\Http\Request;

class TrainingScheduleActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Admin can get all training schedule activities, otherwise only their own
        if(auth()->user() && auth()->user()->role == "admin") {
            return TrainingScheduleActivity::all();
        } else {
            return TrainingScheduleActivity::where('user_id', auth()->user()->id)->get();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Admin can create activities for all users, users can only create their own
        if(auth()->user() && auth()->user()->role == "admin") {
            return TrainingScheduleActivity::create($request->all());
        } else {
            $request->validate([
                'user_id' => 'required',
                'training_schedule_id' => 'required',
                'title' => 'required',
                'description' => 'required',
                'activity_date' => 'required',
            ]);
            $request['user_id'] = auth()->user()->id;
            return TrainingScheduleActivity::create($request->all());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(TrainingScheduleActivity $trainingScheduleActivity)
    {
        //
        if(auth()->user() && (auth()->user()->id == $trainingScheduleActivity->user_id || auth()->user()->role == "admin")) {
            return $trainingScheduleActivity;
        } else {
            return response()->json(['error' => 'Forbidden'], 403);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TrainingScheduleActivity $trainingScheduleActivity)
    {
        //
        if(auth()->user() && (auth()->user()->id == $trainingScheduleActivity->user_id || auth()->user()->role == "admin")) {
            $trainingScheduleActivity->update($request->all());
            return response()->json(['success' => 'Activity updated'], 200);
        } else {
            return response()->json(['error' => 'Forbidden'], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TrainingScheduleActivity $trainingScheduleActivity)
    {
        //
        if(auth()->user() && (auth()->user()->id == $trainingScheduleActivity->user_id || auth()->user()->role == "admin")) {
            $trainingScheduleActivity->delete();
            return response()->json(['success' => 'Activity deleted'], 200);
        } else {
            return response()->json(['error' => 'Forbidden'], 403);
        }
    }
}
