<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

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

    public function sendNotifications($data) {
        $userPushToken = $this->push_token;
        $userEmail = $this->email;
        $userEmailNotifications = $this->email_notifications;

        if($userPushToken) {
            $client = new Client();
            try {
            $response = $client->request('POST', 'https://exp.host/--/api/v2/push/send', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'to' => $userPushToken,
                    'title' => $data['title'],
                    'body' => $data['body'],
                ]
            ]);
            } catch (\Exception $e) {
                \Log::error('Push notification failed: ' . $e->getMessage());
            }
        }

        if ($userEmail && $userEmailNotifications) {
            $appName = config('app.name'); // Hämta appnamnet
            $appUrl = Config::get('app.url');
            $urlEncodedEmail = urlencode($userEmail);
            $unregisterText = "Avregistrera från mejlnotiser? Klicka här: {$appUrl}/unregister-email-notifications.php?email={$urlEncodedEmail} eller avaktivera i appen.";
            $mailFromAddress = config('mail.from.address');
        
            $subject = "{$appName} - " . $data['title'];
            $body = $data['body'] . "\n\n" . $unregisterText;
        
            // Ange headers för charset och MIME-typ
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8" . "\r\n";
            $headers .= "From: {$appName} <{$mailFromAddress}>" . "\r\n";
        
            // Skicka mejlet
            mail($userEmail, $subject, $body, $headers);
        }
    }
}
