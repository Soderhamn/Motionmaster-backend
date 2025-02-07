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
            //Get the latest 100 comments with the user that made the comment
            return LogComment::with('user:id,name,surname,profile_picture_url')->latest()->limit(100)->get();
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

        //Om det är ett svar, skicka pushnotis till den som fick svaret

        if($request->reply_to) {
            $replyTo = LogComment::findOrFail($request->reply_to);
            $replyTo->user->sendNotification([
                'title' => 'Ny kommentar',
                'body' => $user->name . ' har svarat på din kommentar',
            ]);
        }
        // Hämta träningsloggens ägare
        $trainingLog = TrainingLog::findOrFail($request->training_log_id);
        $logOwner = $trainingLog->user;

        // Skicka pushnotis till träningsloggens ägare
        $logOwner->sendNotification([
            'title' => 'Ny kommentar',
            'body' => $user->name . ' har kommenterat din träningslogg',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(LogComment $logComment)
    {
        //Display a specific comment
        //Get the comment with the user that made the comment
        return $logComment->with('user:id,name')->get();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LogComment $logComment)
    {
        //Uppdatera en specifik kommentar
        $logComment->update($request->all());

        return response()->json($logComment, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LogComment $logComment)
    {
        //Radera en specifik kommentar
        $logComment->delete();

        return response()->json(['success' => 'Comment deleted'], 200);

    }
}
