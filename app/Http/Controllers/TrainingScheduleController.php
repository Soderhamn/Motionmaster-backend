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
        //Admin can see all training schedules, users can only see their own AND templates
        if(auth()->user() &&  auth()->user()->role == "admin") {
            return TrainingSchedule::all();
        } else {
            //Get all training schedules for the authenticated user
            //and all templates ()"type":"template" && user_id == null)
            return TrainingSchedule::where('user_id', auth()->user()->id)
                ->orWhere(function($query) {
                    $query->where('type', 'template')
                        ->whereNull('user_id');
                })->get();
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

    public function createFromTemplate(Request $request, $templateId)
    {
        $template = TrainingSchedule::where('type', 'template')->findOrFail($templateId);

        $request->validate([
            'start_date' => 'nullable|date',
        ]);

        //If no start_date is provided, set it to today
        if (!$request->input('start_date')) {
            $request->merge(['start_date' => now()->toDateString()]);
        }
        
        // Skapa en kopia av mallen för användaren
        $newSchedule = $template->replicate();
        $newSchedule->type = 'standard';
        $newSchedule->user_id = auth()->user()->id;
        $newSchedule->template_id = $template->id;
        $newSchedule->start_date = $request->input('start_date'); // Användarens valda startdatum
        $newSchedule->save();

        // Beräkna datum för passen baserat på start_date och jsonData
        $startDate = \Carbon\Carbon::parse($newSchedule->start_date);
        $events = [];
        
        foreach ($newSchedule->jsonData['weeks'] as $weekKey => $weekData) {
            $weekOffset = (int) filter_var($weekKey, FILTER_SANITIZE_NUMBER_INT) - 1; // "week1" -> 0, "week2" -> 1
            foreach ($weekData as $day => $sessions) {
                $dayNumber = (int) $day; // Numeriskt dagvärde (0-6)
                // Beräkna datum: startdatum + antal veckor + justera till rätt dag
                $dayDate = $startDate->copy()->addWeeks($weekOffset)->addDays($dayNumber); // Justera för dag 0 = måndag

                foreach ($sessions as $session) {
                    $events[] = [
                        'date' => $dayDate->toDateString(),
                        'name' => $session['name'],
                    ];
                }
            }
        }

        return response()->json([
            'schedule' => $newSchedule,
            'events' => $events, // Skicka med de beräknade datumen
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(TrainingSchedule $trainingSchedule)
    {
        // Admin kan se alla scheman, användare kan bara se sina egna
        if (auth()->user()) {
            if (auth()->user()->role === 'admin' || $trainingSchedule->user_id === auth()->user()->id) {
                // Beräkna datum för passen baserat på start_date och jsonData
                $startDate = \Carbon\Carbon::parse($trainingSchedule->start_date);
                $events = [];
    
                if ($trainingSchedule->jsonData && isset($trainingSchedule->jsonData['weeks'])) {
                    foreach ($trainingSchedule->jsonData['weeks'] as $weekKey => $weekData) {
                        $weekOffset = (int) filter_var($weekKey, FILTER_SANITIZE_NUMBER_INT) - 1; // "week1" -> 0, "week2" -> 1
                        foreach ($weekData as $day => $sessions) {
                            $dayNumber = (int) $day; // Numeriskt dagvärde (0-6)
                            // Beräkna datum: startdatum + antal veckor + justera till rätt dag
                            $dayDate = $startDate->copy()->addWeeks($weekOffset)->addDays($dayNumber);
    
                            foreach ($sessions as $session) {
                                $events[] = [
                                    'date' => $dayDate->toDateString(),
                                    'name' => $session['name'],
                                ];
                            }
                        }
                    }
                }
    
                return response()->json([
                    'schedule' => $trainingSchedule,
                    'events' => $events, // Skicka med de beräknade datumen
                ]);
            } else {
                return response()->json(['error' => 'Forbidden'], 403);
            }
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
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
