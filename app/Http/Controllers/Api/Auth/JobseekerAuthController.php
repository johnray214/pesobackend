<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Jobseeker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class JobseekerAuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:jobseekers|max:191',
            'password' => 'required|string|min:8|confirmed',
            'contact' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'sex' => 'nullable|in:male,female',
            'date_of_birth' => 'nullable|date',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = 'active';
        $validated['otp_code'] = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $validated['otp_expires_at'] = now()->addMinutes(15);

        $jobseeker = Jobseeker::create($validated);

        $this->sendOtpEmail($jobseeker);

        $token = $jobseeker->createToken('jobseeker-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'jobseeker' => $jobseeker,
                'token' => $token,
            ],
            'message' => 'Registration successful. Please verify your email.',
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $jobseeker = Jobseeker::where('email', $request->email)->first();

        if (!$jobseeker || !Hash::check($request->password, $jobseeker->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        $token = $jobseeker->createToken('jobseeker-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'jobseeker' => $jobseeker,
                'token' => $token,
            ],
            'message' => 'Login successful',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()->load('skills'),
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:jobseekers,email']);

        $status = \Illuminate\Support\Facades\Password::broker('jobseekers')->sendResetLink($request->only('email'));

        if ($status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT) {
            return response()->json(['success' => true, 'message' => 'Password reset link sent to your email.']);
        }

        return response()->json(['success' => false, 'message' => 'Failed to send reset link.'], 400);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = \Illuminate\Support\Facades\Password::broker('jobseekers')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => \Illuminate\Support\Facades\Hash::make($password)
                ])->setRememberToken(\Illuminate\Support\Str::random(60));
                $user->save();
            }
        );

        if ($status === \Illuminate\Support\Facades\Password::PASSWORD_RESET) {
            return response()->json(['success' => true, 'message' => 'Password has been securely reset.']);
        }

        return response()->json(['success' => false, 'message' => 'Failed to reset password, link might be invalid or expired.'], 400);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|string|size:6',
        ]);

        $jobseeker = Jobseeker::where('email', $request->email)->first();

        if (!$jobseeker) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        if ($jobseeker->hasVerifiedEmail()) {
            return response()->json(['success' => false, 'message' => 'Email already verified'], 400);
        }

        if ($jobseeker->otp_code !== $request->otp_code) {
            return response()->json(['success' => false, 'message' => 'Invalid verification code'], 400);
        }

        if (now()->greaterThan($jobseeker->otp_expires_at)) {
            return response()->json(['success' => false, 'message' => 'Verification code has expired'], 400);
        }

        $jobseeker->update([
            'email_verified_at' => now(),
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully',
        ]);
    }

    public function resendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $jobseeker = Jobseeker::where('email', $request->email)->first();

        if (!$jobseeker) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        if ($jobseeker->hasVerifiedEmail()) {
            return response()->json(['success' => false, 'message' => 'Email already verified'], 400);
        }

        $jobseeker->update([
            'otp_code' => str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'otp_expires_at' => now()->addMinutes(15),
        ]);

        $this->sendOtpEmail($jobseeker);

        return response()->json([
            'success' => true,
            'message' => 'A new verification code has been sent to your email.',
        ]);
    }

    private function sendOtpEmail($jobseeker)
    {
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
                                'Email' => $jobseeker->email,
                                'Name'  => $jobseeker->first_name
                            ]
                        ],
                        'TemplateID' => 7861324,
                        'TemplateLanguage' => true,
                        'Subject' => 'Verify Your Email — PESO Jobseeker',
                        'Variables' => [
                            'first_name' => $jobseeker->first_name,
                            'otp_code'   => $jobseeker->otp_code,
                            'verify_url' => env('FRONTEND_URL', 'http://localhost:8080') . '/jobseeker/verify-email?email=' . urlencode($jobseeker->email) . '&otp=' . $jobseeker->otp_code
                        ]
                    ]
                ]
            ];
            $response = $mj->post(\Mailjet\Resources::$Email, ['body' => $body]);
            if (!$response->success()) {
                \Illuminate\Support\Facades\Log::error('Mailjet API Error (Jobseeker OTP): ' . json_encode($response->getData()));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Mailjet Exception (Jobseeker OTP): ' . $e->getMessage());
        }
    }
}
