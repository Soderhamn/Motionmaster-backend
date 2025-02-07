<?php

namespace App\Http\Controllers;

use App\Models\TrainingGoal;
use Illuminate\Http\Request;

class TrainingGoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all training goals, admin can see all, user can only see their own
        $trainingGoals = auth()->user()->role == "admin" ? TrainingGoal::all() : auth()->user()->trainingGoals;

        return response()->json($trainingGoals, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Skapa nytt träningsmål, admin kan skapa för alla, användare kan bara skapa för sig själv
        //Validate the request
        $request->validate([
            'user_id' => 'nullable|integer',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'nullable|in:not_started,active,completed,failed',
            'progress' => 'nullable|integer',
            'target' => 'nullable|integer',
            'goal_type' => 'nullable|in:weight_loss,endurance_gain,muscle_gain,strength_gain,flexibility_gain,other',
            'archived' => 'nullable|in:yes,no',
            'type' => 'nullable|in:standard,template',
            'template_id' => 'nullable|integer',
            'premium_level' => 'nullable|integer',
        ]);

        //If the user is Admin, select user id from request, otherwise use the authenticated user
        $userId = auth()->user()->role == "admin" ? $request->user_id : auth()->id();

        $trainingGoal = new TrainingGoal();
        $trainingGoal->user_id = $userId;
        $trainingGoal->title = $request->title;
        $trainingGoal->description = $request->description ?? null;
        $trainingGoal->start_date = $request->start_date ?? null;
        $trainingGoal->end_date = $request->end_date ?? null;
        $trainingGoal->status = $request->status ?? 'active';
        $trainingGoal->progress = $request->progress ?? 0;
        $trainingGoal->target = $request->target ?? 100;
        $trainingGoal->goal_type = $request->goal_type ?? 'other';
        $trainingGoal->archived = $request->archived ?? 'no';
        $trainingGoal->type = $request->type ?? 'standard';
        $trainingGoal->template_id = $request->template_id ?? null;
        $trainingGoal->premium_level = $request->premium_level ?? 0;
        $trainingGoal->save();

        return response()->json($trainingGoal, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TrainingGoal $trainingGoal)
    {
        //Show a specific training goal, admin can see all, user can only see their own
        if (auth()->user()->role == "admin" || auth()->id() == $trainingGoal->user_id) {
            return response()->json($trainingGoal, 200);
        } else {
            return response()->json(['message' => 'Forbidden'], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TrainingGoal $trainingGoal)
    {
        //
        if(auth()->user()->role == "admin" || auth()->id() == $trainingGoal->user_id) {
            $trainingGoal->update($request->all());
            return response()->json($trainingGoal, 200);
        } else {
            return response()->json(['message' => 'Forbidden'], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TrainingGoal $trainingGoal)
    {
        //
        if(auth()->user()->role == "admin" || auth()->id() == $trainingGoal->user_id) {
            $trainingGoal->delete();
            return response()->json(['message' => 'Training goal deleted'], 200);
        } else {
            return response()->json(['message' => 'Forbidden'], 403);
        }
    }
}
