<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recepie;

class RecepieController extends Controller
{
    //Lista alla recept
    public function index()
    {
        return Recepie::all();
    }

    //Visa ett specifikt recept
    public function show(Recepie $recepie)
    {
        return $recepie;
    }

    //Skapa ett nytt recept
    public function store(Request $request)
    {
                //Användaren måste vara admin för att kunna skapa recept
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'html_content' => 'nullable|string',
            'premium_level' => 'nullable|integer',
        ]);

        $recepie = Recepie::create($request->only([
            'title',
            'short_description',
            'html_content',
            'premium_level',
        ]));
        return response()->json($recepie, 201);
    }

    //Uppdatera ett recept
    public function update(Request $request, Recepie $recepie)
    {
        //Användaren måste vara admin för att kunna uppdatera övningar
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Du har inte behörighet att uppdatera detta recept'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'html_content' => 'nullable|string',
            'premium_level' => 'nullable|integer',
        ]);

        $recepie->update($request->only([
            'title',
            'short_description',
            'html_content',
            'premium_level',
        ]));

        return response()->json($recepie, 200);
    }

    //Radera ett recept
    public function destroy(Recepie $recepie)
    {
        //Användaren måste vara admin för att kunna ta bort
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Du har inte behörighet att ta bort detta recept'], 403);
        }

        $recepie->delete();
        return response()->json(['message' => 'Receptet har tagits bort']);
    }

}
