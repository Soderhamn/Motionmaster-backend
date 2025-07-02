<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationMail;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'email_verified_at',
        'password',
        'name',
        'surname',
        'weight',
        'height',
        'dob',
        'training_level',
        'sport',
        'role',
        'push_token',
        'email_notifications',
        'device_type',
        'external_auth_provider',
        'external_auth_id',
        'profile_picture_url',
        'email_verification_code',
        'password_reset_code',
        'password_reset_code_created_at',
        'premium_level',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function trainingGoals()
    {
        return $this->hasMany(TrainingGoal::class);
    }

    public function trainingSchedules()
    {
        return $this->hasMany(TrainingSchedule::class);
    }

    public function trainingLogs()
    {
        return $this->hasMany(TrainingLog::class);
    }

    public function logComments()
    {
        return $this->hasMany(LogComment::class);
    }

    public function sendNotifications($data) {

        if($this->push_token) {
            $client = new Client();
            try {
            $response = $client->request('POST', 'https://exp.host/--/api/v2/push/send', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'to' => $this->push_token,
                    'title' => $data['title'],
                    'body' => $data['body'],
                ]
            ]);
            } catch (\Exception $e) {
                \Log::error('Push notification failed: ' . $e->getMessage());
            }
        }

        if ($this->email && $this->email_notifications) {
            $urlEncodedEmail = urlencode($this->email);
        
            // Skicka mejlet
            Mail::to($this->email)->send(new NotificationMail(
                $this->name,
                $data['title'],
                $body,
                "{$appUrl}/unregister-email-notifications.php?email={$urlEncodedEmail}"
            ));
        }
    }
}
