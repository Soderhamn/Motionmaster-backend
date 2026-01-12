<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    // List all exercises
    public function index()
    {
        return Exercise::all();
    }

    // Show a single exercise
    public function show(Exercise $exercise)
    {
        return $exercise;
    }

    public function displayExerciseView(Exercise $exercise)
    {
        return view('app.exercises', ['exercise' => $exercise]);
    }

    // Create a new exercise
    public function store(Request $request)
    {
        //Användaren måste vara admin för att kunna skapa övningar
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'html_content' => 'nullable|string',
            'premium_level' => 'nullable|integer',
        ]);

        $exercise = Exercise::create($request->only([
            'title',
            'short_description',
            'html_content',
            'premium_level',
        ]));
        return response()->json($exercise, 201);
    }

    // Update an exercise
    public function update(Request $request, Exercise $exercise)
    {
        //Användaren måste vara admin för att kunna uppdatera övningar
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'html_content' => 'nullable|string',
            'premium_level' => 'nullable|integer',
        ]);

        $exercise->update($request->only([
            'title',
            'short_description',
            'html_content',
            'premium_level',
        ]));
        
        return response()->json($exercise, 200);
    }

    // Delete an exercise
    public function destroy(Exercise $exercise)
    {
        //Användaren måste vara admin för att kunna ta bort övningar
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Du har inte behörighet att ta bort denna övning'], 403);
        }

        $exercise->delete();
        return response()->json(['message' => 'Övningen har tagits bort']);
    }
}