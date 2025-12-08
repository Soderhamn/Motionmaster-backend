<?php

namespace App\Http\Controllers;

use App\Models\CalorieEntries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CalorieEntriesController extends Controller
{

    //Return ALL calorie entries for a user
    public function index(User $user)
    {
        return CalorieEntries::where('user_id', $user->id)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            "foodName" => "required",
            "calories" => "required",
            "date" => "required",
        ]);

        //Läs in data från request
        $data = [
            "foodName" => $request->foodName,
            "calories" => $request->calories,
            "date" => $request->date,
            "user_id" => auth()->id(),
        ];

        //Lägg till extra data om det finns
        if($request->caloriesPer100g) {
            $data["caloriesPer100g"] = $request->caloriesPer100g;
        }
        if($request->weight) {
            $data["weight"] = $request->weight;
        }

        //Skapa en ny post
        return CalorieEntries::create($data);
    }

    //Hämta alla Calorie Entries för den inloggade användaren den senaste veckan, summera antal kalorier dag för dag. Returnera som en lista med datum och totala kalorier för varje dag. samt en lista med dagens calorie entries och en lista med övriga datums calorie entries
    public function getWeeklyCalorieSummary()
    {
        $userId = auth()->id();
        $today = date('Y-m-d');
        $weekAgo = date('Y-m-d', strtotime('-7 days'));

        $entries = CalorieEntries::where('user_id', $userId)
            ->whereBetween('date', [$weekAgo, $today])
            ->orderBy('date', 'asc')
            ->get();

        $dailyTotals = [];
        $todayEntries = [];
        $otherEntries = [];

        foreach ($entries as $entry) {
            // Summera kalorier per dag
            if (!isset($dailyTotals[$entry->date])) {
                $dailyTotals[$entry->date] = 0;
            }
            $dailyTotals[$entry->date] += $entry->calories;

            // Dela upp dagens entries och övriga
            if ($entry->date === $today) {
                $todayEntries[] = $entry;
            } else {
                $otherEntries[] = $entry;
            }
        }

        return response()->json([
            'dailyTotals' => $dailyTotals,
            'todayEntries' => $todayEntries,
            'otherEntries' => $otherEntries,
        ]);
    }

    //Radera en post utifrån ID
    public function destroy(CalorieEntries $calorieEntries)
    {
        if($calorieEntries->user_id != auth()->id()) {
            return response()->json("Du har inte behörighet att radera denna post", 403);
        }

        $calorieEntries->delete();
        return response()->json("Kaloriinmatning raderad", 200);
    }
}
