<?php

namespace App\Http\Controllers;

use App\Models\TrainingLog;
use Illuminate\Http\Request;

class TrainingLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Admin can see all training logs, users can only see their own
        if(auth()->user() && auth()->user()->role == "admin") {
            return TrainingLog::with(['user:id,name'])->withCount('comments')->orderBy('date', 'desc')->take(1000)->get(); //Kan se de senaste 1000 loggarna
        } else {
            return TrainingLog::where('user_id', auth()->user()->id)->withCount('comments')->where('type', '!=', 'template')->orderBy('date', 'desc')->take(100)->get(); //Kan se de senaste 100 loggarna
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validate the request
        $request->validate([
            'user_id' => 'nullable|integer',
            'date' => 'nullable|date',
            'duration' => 'nullable|string',
            'training_schedule_id' => 'nullable|integer',
            'rating' => 'nullable|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'activities' => 'nullable|json',
            'type' => 'nullable|in:standard,template',
        ]);

        // Admin can create training logs for all users, users can only create their own
        if(auth()->user() &&  auth()->user()->role == "admin") {
            //If user_id is not set, set it to the authenticated user, otherwise use the user_id from the request
            if(!$request->user_id) {
                $request['user_id'] = auth()->user()->id;
            }
            return TrainingLog::create($request->all());
        } else {
            $request['user_id'] = auth()->user()->id;
            return TrainingLog::create($request->all());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TrainingLog $trainingLog)
    {
        //Find the training log, with all the comments
        $trainingLog = TrainingLog::with(['comments' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($trainingLog->id);

        // Admin can see all training logs, users can only see their own
        if(auth()->user() &&  auth()->user()->role == "admin") {
            return $trainingLog;
        } else {
            if($trainingLog->user_id == auth()->user()->id) {
                return $trainingLog;
            } else {
                return response()->json(['error' => 'Forbidden'], 403);
            }
        }
    }

    public function showTemplates()
    {
        //Return all training logs of type template
        return TrainingLog::where('type', 'template')->where('user_id', auth()->user()->id)->with(['user:id,name'])->orderBy('date', 'desc')->get();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TrainingLog $trainingLog)
    {
        //A user can only update their own training logs, an admin can update any training log
        if(auth()->user() &&  auth()->user()->role == "admin") {
            $trainingLog->update($request->all());
            return $trainingLog;
        } else {
            if($trainingLog->user_id == auth()->user()->id) {
                $trainingLog->update($request->all());
                return $trainingLog;
            } else {
                return response()->json(['error' => 'Forbidden'], 403);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TrainingLog $trainingLog)
    {
        //A user can only delete their own training logs, an admin can delete any training log
        //Find the training log
        $trainingLog = TrainingLog::findOrFail($trainingLog->id);

        if(auth()->user() &&  auth()->user()->role == "admin") {
            $trainingLog->delete();
            return response()->json(['message' => 'Training log deleted'], 200);
        } else {
            if($trainingLog->user_id == auth()->user()->id) {
                $trainingLog->delete();
                return response()->json(['message' => 'Training log deleted'], 200);
            } else {
                return response()->json(['error' => 'Forbidden'], 403);
            }
        }
    }
}
