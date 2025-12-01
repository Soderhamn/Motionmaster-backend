<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TrainingLogController;
use App\Http\Controllers\TrainingScheduleController;
use App\Http\Controllers\LogCommentController;
use App\Http\Controllers\TrainingGoalController;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return response()->json(['message' => 'API is online!'], 200);
});

//Auth routes (login, register, logout), do not require authentication
Route::post("/login", [UserController::class, 'login']); //Login user
Route::post("/register", [UserController::class, 'register']); //Register user
Route::post("/logout", [UserController::class, 'logout'])->middleware('auth:sanctum'); //Logout user, requires authentication

//All other routes require authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::get("/adminDashboard", [UserController::class, 'adminDashboard']); //Get admin dashboard data

    //User routes
    Route::get("/users", [UserController::class, 'index']); //Get all users
    Route::post("users/changePassword", [UserController::class, 'changePassword']); //Change password for the authenticated user
    Route::post("users/pushToken", [UserController::class, 'pushToken']); //Update push token for the authenticated user
    Route::get("/users/{user}", [UserController::class, 'show']); //Get a specific user
    Route::put("/users/{user}", [UserController::class, 'update']); //Update a specific user
    Route::delete("/users/{user}", [UserController::class, 'destroy']); //Delete a specific user
    Route::get("/admin/users/{user}", [UserController::class, 'adminGetUser']); //Get a specific user for admin panel
    Route::post("/admin/upgrade-to-premium", [UserController::class, 'adminUpgradeToPremium']); //Upgrade a user to premium in admin panel

    //Training log routes
    Route::get("/traininglogs", [TrainingLogController::class, 'index']); //Get all training logs
    Route::get("/traininglogs/{trainingLog}", [TrainingLogController::class, 'show']); //Get a specific training log
    Route::post("/traininglogs", [TrainingLogController::class, 'store']); //Create a new training log
    Route::put("/traininglogs/{trainingLog}", [TrainingLogController::class, 'update']); //Update a specific training log
    Route::delete("/traininglogs/{trainingLog}", [TrainingLogController::class, 'destroy']); //Delete a specific training log
    Route::get("/traininglogs/user/{user}", [TrainingLogController::class, 'getUserTrainingLogs']); //Get all training logs for a specific user

    //Training schedule routes
    Route::get("/trainingschedules", [TrainingScheduleController::class, 'index']); //Get all training schedules
    Route::get("/getActiveSchedule", [TrainingScheduleController::class, 'getActiveTrainingSchedule']); //Get the active training schedule for the logged in user
    Route::get("/trainingschedules/{trainingSchedule}", [TrainingScheduleController::class, 'show']); //Get a specific training schedule
    Route::post("/trainingschedules", [TrainingScheduleController::class, 'store']); //Create a new training schedule
    //Create a training schedule from a template
    Route::post("/trainingschedules/{templateId}", [TrainingScheduleController::class, 'createFromTemplate']); //Create a new training schedule from a template
    Route::put("/trainingschedules/{trainingSchedule}", [TrainingScheduleController::class, 'update']); //Update a specific training schedule
    Route::delete("/trainingschedules/{trainingSchedule}", [TrainingScheduleController::class, 'destroy']); //Delete a specific training schedule
    Route::get("/trainingschedules/user/{user}", [TrainingScheduleController::class, 'getUserTrainingSchedules']); //Get all training schedules for a specific user

    //Log comment routes
    Route::get("/logcomments", [LogCommentController::class, 'index']); //Get all comments (latest 100, in Admin panel)
    Route::get("/logcomments/traininglog/{trainingLog}", [LogCommentController::class, 'getLogComments']); //Get all comments for a specific training log
    Route::get("/logcomments/{logComment}", [LogCommentController::class, 'show']); //Get a specific comment
    Route::post("/logcomments", [LogCommentController::class, 'store']); //Create a new comment
    Route::put("/logcomments/{logComment}", [LogCommentController::class, 'update']); //Update a specific comment
    Route::delete("/logcomments/{logComment}", [LogCommentController::class, 'destroy']); //Delete a specific comment
    

    //User training goals routes
    Route::get("/traininggoals", [TrainingGoalController::class, 'index']); //Get all user training goals
    Route::get("/traininggoals/{trainingGoal}", [TrainingGoalController::class, 'show']); //Get a specific user training goal
    Route::post("/traininggoals", [TrainingGoalController::class, 'store']); //Create a new user training goal
    Route::put("/traininggoals/{trainingGoal}", [TrainingGoalController::class, 'update']); //Update a specific user training goal
    Route::delete("/traininggoals/{trainingGoal}", [TrainingGoalController::class, 'destroy']); //Delete a specific user training goal
    //Route::get("/traininggoals/user/{user}", [TrainingGoalController::class, 'getUserTrainingGoals']); //Get all user training goals for a specific user

    //Send email and push notifications to users
    Route::post("/sendemailnotifications", [UserController::class, 'sendEmailNotifications']); //Send email to ALL users
    Route::post("/sendpushnotifications", [UserController::class, 'sendPushNotifications']); //Send push notification to ALL users

    //Endpoint for Help
    Route::post("/help", function (Request $request) {
        // Handle the help request here
        // You can send an email or create a ticket in your support system
        $type = $request->input('type');
        $message = $request->input('message');
        $user = auth()->user();
        $to = $type == "app" ? ["info@jandrankalanfjord.se", "support@sandarnecreations.com"] : ["info@jandrankalanfjord.se"];
        $subject = "Motionmaster - Supportärende från " . $user->name;

        $body = "Användare: " . $user->name . "\n" .
                "E-post: " . $user->email . "\n" .
                "Typ: " . $type . "\n" .
                "Meddelande: " . htmlspecialchars($message) . "\n";

        /*mail($to, $subject, $body, [
            'From' => 'app@motionmaster.sandarnecreations.com',
            'Reply-To' => $user->email,
            'Content-Type' => 'text/plain; charset=utf-8'
        ]);*/

        Mail::raw($body, function ($mail) use ($to, $subject, $user) {
            $mail->to($to)
                 ->subject($subject)
                 ->from('app@motionmaster.sandarnecreations.com', 'Motionmaster Support')
                 ->replyTo($user->email);

        });

        return response()->json(['message' => 'Supportärende mottaget!'], 200);
    })->name('help');
        
});