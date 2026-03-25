<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Jobseeker extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'otp_code',
        'otp_expires_at',
        'password',
        'contact',
        'address',
        'province_code',
        'province_name',
        'city_code',
        'city_name',
        'barangay_code',
        'barangay_name',
        'street_address',
        'sex',
        'date_of_birth',
        'bio',
        'resume_path',
        'education_level',
        'job_experience',
        'certificate_path',
        'barangay_clearance_path',
        'avatar_path',
        'latitude',
        'longitude',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['full_name'];

    protected $casts = [
        'password' => 'hashed',
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function skills()
    {
        return $this->hasMany(JobseekerSkill::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function notificationReads()
    {
        return $this->morphMany(NotificationRead::class, 'recipient');
    }

    public function fullName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function sendPasswordResetNotification($token)
    {
        $frontendUrl = env('FRONTEND_URL', 'http://localhost:8080');
        $resetUrl = $frontendUrl . '/jobseeker/reset-password?token=' . $token . '&email=' . urlencode($this->email);
        $name = $this->first_name;

        try {
            $mj = new \Mailjet\Client(env('MAILJET_API_KEY'), env('MAILJET_SECRET_KEY'), true, ['version' => 'v3.1']);
            $body = [
                'Messages' => [
                    [
                        'From' => [
                            'Email' => env('MAILJET_FROM_EMAIL', 'yujohnray96@gmail.com'),
                            'Name'  => env('MAILJET_FROM_NAME', 'PESO')
                        ],
                        'To' => [
                            [
                                'Email' => $this->email,
                                'Name'  => $name
                            ]
                        ],
                        'TemplateID' => 7861338,
                        'TemplateLanguage' => true,
                        'Subject' => 'Reset Your Password — PESO',
                        'Variables' => [
                            'first_name'   => $name,
                            'email'        => $this->email,
                            'reset_url'    => $resetUrl,
                            'request_time' => now()->format('F j, Y, g:i A')
                        ]
                    ]
                ]
            ];
            $mj->post(\Mailjet\Resources::$Email, ['body' => $body]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Mailjet Exception (Jobseeker Password Reset): ' . $e->getMessage());
        }
    }
}
