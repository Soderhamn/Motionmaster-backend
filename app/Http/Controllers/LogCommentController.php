<?php

namespace App\Http\Controllers;

use App\Models\LogComment;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TrainingLog;

class LogCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user() &&  auth()->user()->role == "admin") {
            //Get the latest 500 comments with the user that made the comment
            return LogComment::with('user:id,name')->withCount(['replies' => function ($query) {
                $query->whereNotNull('reply_to'); // Räkna bara kommentarer med reply_to
            }])->latest()->limit(500)->get();
        } else if (auth()->user()) {
            // Get training logs belonging to the authenticated user that have comments from anyone
            return TrainingLog::where('user_id', auth()->user()->id) // Träningsloggar som tillhör auth user
                ->whereHas('comments') // Kontrollera att det finns kommentarer kopplade till loggen
                ->with(['comments' => function ($query) {
                    $query->with('user:id,name') // Ladda användaren som gjorde kommentaren
                        ->orderBy('created_at', 'desc') // Sortera kommentarerna
                        ->take(100); // Begränsa till de senaste 100 kommentarerna
                }])
                ->get();
        } else {
            return response()->json(['error' => 'Forbidden'], 403);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userId = auth()->id(); // Den som skapar kommentaren
        //Hämta inloggad användare
        $user = User::findOrFail($userId);

        // Skapa kommentaren
        $logComment = LogComment::create([
            'user_id' => $userId,
            'training_log_id' => $request->training_log_id,
            'comment' => $request->comment,
            'reply_to' => $request->reply_to ?? null,
        ]);

        
        try {
            if($request->reply_to) { //Om det är ett svar, skicka pushnotis till den som fick svaret
                $replyTo = LogComment::findOrFail($request->reply_to);
                $replyTo->user->sendNotifications([
                    'title' => 'Ny kommentar',
                    'body' => $user->name . ' har svarat på din kommentar',
                ]);
            }
            // Hämta träningsloggens ägare
            $trainingLog = TrainingLog::findOrFail($request->training_log_id);
            $logOwner = $trainingLog->user;

            // Skicka pushnotis till träningsloggens ägare
            $logOwner->sendNotifications([
                'title' => 'Ny kommentar',
                'body' => $user->name . ' har kommenterat din träningslogg',
            ]);
        } catch (\Exception $e) {
            // Om det inte går att skicka pushnotis, logga felet
            \Log::error('Failed to send notification: ' . $e->getMessage());
        }
        // Returnera den skapade kommentaren
        return response()->json($logComment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, LogComment $logComment)
    {
        //Display a specific comment
        //Get the comment with the user that made the comment
        //If the user is not Admin or the owner of the comment, return 403

        if(auth()->user() &&  auth()->user()->role == "admin") {
            return $logComment->with('user:id,name')->get();
        } else if (auth()->user() && auth()->user()->id == $logComment->user_id) {
            return $logComment->with('user:id,name')->get();
        } else {
            return response()->json(['error' => 'Forbidden'], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LogComment $logComment)
    {
        //Uppdatera en specifik kommentar, checka om användaren är admin eller ägaren av kommentaren

        if(auth()->user() &&  auth()->user()->role == "admin") {
            $logComment->update($request->all());
        } else if (auth()->user() && auth()->user()->id == $logComment->user_id) {
            $logComment->update($request->all());
        } else {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return response()->json($logComment, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LogComment $logComment)
    {
        //Radera en specifik kommentar, kolla om användaren är admin eller ägaren av kommentaren

        if(auth()->user() &&  auth()->user()->role == "admin") {
            $logComment->delete();
        } else if (auth()->user() && auth()->user()->id == $logComment->user_id) {
            $logComment->delete();
        } else {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return response()->json(['success' => 'Comment deleted'], 200);

    }
}
