<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class UserController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Get all users
        if(auth()->user() &&  auth()->user()->role == "admin") {
            return User::all();
        } else {
            return response()->json(['error' => 'Forbidden'], 403);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function register(Request $request)
    {
        //Create a new user
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|max:128',
            'name' => 'required',
            'device' => 'required',
        ]);

        $name = $request->name;
        $email = $request->email;
        $password = Hash::make($request->password, [
            'rounds' => 12
        ]);
        if(auth()->user() &&  auth()->user()->role == "admin") { //Admin can set role, otherwise default is user
            $role = $request->role;
        } else {
            $role = 'user';
        }
        $randomCode = rand(100000, 999999);

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
            'email_verification_code' => $randomCode,
            'device_type' => $request->device,
        ]);

        //Try to send email verification code, plain text only

        /*$headers = [
            'Content-Type' => 'text/plain; charset=utf-8',
            'X-Mailer' => 'SC Mailer 1.0',
            'From' => Config::get('mail.from.address'),
        ];*/
        
        try {
            //mail($email, 'Välkommen till Motionmaster - bekräfta din mailadress', 'Din bekräftelsekod är: ' . $randomCode . '. Ange denna kod i appen för att bekräfta din mailadress.', $headers);

            //Send email with Laravel Mail
            Mail::to($email)->send(new WelcomeMail($name, $email, $randomCode));
        } catch (\Exception $e) {
            // Log the error message
            \Log::error('Failed to send email: ' . $e->getMessage());
        }

        //Return the user in JSON format
        return response()->json($user);    
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //Get a specific user, the user can view their own profile or an admin can view any profile
        if(auth()->user() && (auth()->user()->id == $user->id || auth()->user()->role == "admin")) {
            return $user;
        } else {
            return response()->json(['error' => 'Forbidden'], 403);
        }
    }

    public function adminGetUser($userId)
    {
        //Admin can get any user, this is for the admin panel and includes goals and training schedules and the last 100 training logs
        //This is used in the admin panel to view a specific user
        if(auth()->user() && auth()->user()->role == "admin") {
            $user = User::with(['trainingGoals', 'trainingSchedules', 'trainingLogs' => function($query) {
                $query->orderBy('created_at', 'desc')->take(100)->withCount('comments');
            }])->findOrFail($userId);

            return response()->json($user, 200);
        } else {
            return response()->json(['error' => 'Forbidden'], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //Update a specific user, the user can update their own profile or an admin can update any profile
        if(auth()->user() && (auth()->user()->id == $user->id || auth()->user()->role == "admin")) {
            $user->update($request->all());
            return response()->json($user, 200);
        } else {
            return response()->json(['error' => 'Forbidden'], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //Delete a specific user, the user can delete their own profile or an admin can delete any profile
        if(auth()->user() && (auth()->user()->id == $user->id || auth()->user()->role == "admin")) {
            $user->delete();
            return response()->json(['success' => 'User deleted'], 200);
        } else {
            return response()->json(['error' => 'Forbidden'], 403);
        }
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        //Login user, return useer info + token
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        //Make email non case sensitive
        $email = strtolower($request->email);

        $user = User::where('email', $email)->first();

        if(!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Wrong email or password'], 401);
        }

        //Update user with logged in device type for statistics, if provided
        if($request->device)
        {
            $user->device_type = $request->device;
            $user->save();
        }

        $token = $user->createToken('auth_token')->plainTextToken;  

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        //Logout user, destroy token
        if(!auth()->user()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        auth()->user()->tokens()->delete();

        return response()->json(['success' => 'Logged out'], 200);
    }

    //Send email and push notifications to ALL users
    public function sendEmailNotifications(Request $request)
    {
        //Send email to ALL users
        if(auth()->user() &&  auth()->user()->role == "admin") {
            $users = User::whereNotNull('email_verified_at')->where('email_notifications', 1); //Check if email is verified and email notifications are enabled
            $subject = $request->subject;
            $message = $request->message;
            //$txtMessage = $request->txtMessage;

            $headers = [
                'Content-Type' => 'text/html charset=utf-8',
                'X-Mailer' => 'SC Mailer 1.0',
                'From' => Config::get('mail.from.address'),
            ];

            foreach($users as $user) {
                try {
                    mail($user->email, $subject, $message, $headers);
                } catch (\Exception $e) {
                    //Log
                    \Log::error('Failed to send email: ' . $e->getMessage());
                }
            }

            return response()->json(['success' => 'Emails sent'], 200);
        } else {
            return response()->json(['error' => 'Forbidden'], 403);
        }
    }

    //Send push notifications to ALL users
    public function sendPushNotifications(Request $request)
    {
        //Send push notification to ALL users
        if(auth()->user() &&  auth()->user()->role == "admin") {
            $users = User::whereNotNull('push_token');
            $title = $request->title;
            $body = $request->message;

            //If there is no users, return error
            if($users->count() == 0) {
                return response()->json(['error' => 'No users with push tokens found'], 404);
            }

            $client = new Client();
            foreach($users as $user) {
                try {
                    $response = $client->request('POST', 'https://exp.host/--/api/v2/push/send', [
                        'headers' => [
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json',
                        ],
                        'json' => [
                            'to' => $user->push_token,
                            'title' => $title,
                            'body' => $body,
                        ]
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Failed to send notification: ' . $e->getMessage());
                }
            }
            return response()->json(['success' => 'Push notifications sent'], 200);
        } else {
            return response()->json(['error' => 'Forbidden'], 403);
        }
    }

    public function adminDashboard() {
        //Get admin dashboard data
        if(auth()->user() &&  auth()->user()->role == "admin") {
            $usersCount = User::count();
            $trainingLogsCount = \App\Models\TrainingLog::count();
            $trainingGoalsCount = \App\Models\TrainingGoal::count();
            $trainingSchedulesCount = \App\Models\TrainingSchedule::count();
            $logCommentsCount = \App\Models\LogComment::count();

            return response()->json([
                'users_count' => $usersCount,
                'training_logs_count' => $trainingLogsCount,
                'training_goals_count' => $trainingGoalsCount,
                'training_schedules_count' => $trainingSchedulesCount,
                'log_comments_count' => $logCommentsCount,
            ], 200);
        } else {
            return response()->json(['error' => 'Forbidden'], 403);
        }
    }
}
