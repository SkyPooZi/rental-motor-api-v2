<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Google;
use App\Models\Facebook;
use Illuminate\Http\Request;
use App\Services\Otp\OtpService;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\Google\GoogleService;
use Illuminate\Support\Facades\Config;
use Laravel\Socialite\Facades\Socialite;
use App\Services\Facebook\FacebookService;

class AuthController extends Controller
{
    // User Services | Otp Services | Google Services | Facebook Services
    private $userService, $otpService, $googleService, $facebookService;

    public function __construct(
        UserService $userService,
        OtpService $otpService,
        GoogleService $googleService,
        FacebookService $facebookService,
    ) {
        $this->userService = $userService;
        $this->otpService = $otpService;
        $this->googleService = $googleService;
        $this->facebookService = $facebookService;
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = User::find(Auth::user()->id);

            if ($user->is_banned) {
                Auth::logout();
                return response()->json([
                    "success" => false,
                    "code" => 500,
                    "error" => 'Akun Anda telah diblokir. Silakan hubungi administrator.',
                ], 500);
            }

            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json([
                "success" => true,
                "code" => 200,
                "token" => $token,
                "data" => $user,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "code" => 500,
                "error" => 'Email atau Password yang anda berikan salah.',
            ], 500);
        }
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleRedirectUri = Config::get('services.login.redirect');

        if (request()->has('error')) {
            return redirect($googleRedirectUri . '/login')->with([
                'status' => 400,
                'message' => 'Terjadi kesalahan saat login akun dengan google',
                'error' => [
                    'type' => 'Kesalahan Autentikasi',
                    'details' => 'Autentikasi google gagal karena kredensial tidak valid atau masalah jaringan.',
                ]
            ], 400);
        }

        $google = Socialite::driver('google')->user();

        $user = User::where('email', $google->getEmail())->first();

        if (!$user) {
            $newUser = $this->userService->createUserWithRole([
                'username' => $google->getName(),
                'email' => $google->getEmail(),
                'password' => Hash::make($this->generateRandomPassword(15)),
            ], 'user');

            $token = $user->createToken('api_token')->plainTextToken;

            $this->googleService->insertData([
                'access_token' => $token,
                'pengguna_id' => $newUser->id,
                'tanggal_masuk' => now(),
            ]);

            return redirect($googleRedirectUri . '/login/google')->with([
                "success" => true,
                "code" => 200,
                "token" => $token,
                "data" => $user,
            ], 200);
        }

        if ($user->is_banned) {
            Auth::logout();
            return response()->json([
                "success" => false,
                "code" => 500,
                "error" => 'Akun Anda telah diblokir. Silakan hubungi administrator.',
            ], 500);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        $google = Google::where('user_id', $user->id)->first();

        if ($google) {
            $this->googleService->update($google->id, [
                'access_token' => $token,
                'login_date' => now(),
            ]);
        }

        return redirect($googleRedirectUri . '/login/google')->with([
            "success" => true,
            "code" => 200,
            "token" => $token,
            "data" => $user,
        ], 200);
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        $facebookRedirectUri = Config::get('services.login.redirect');

        if (request()->has('error')) {
            return redirect($facebookRedirectUri . '/login')->with([
                'status' => 400,
                'message' => 'Terjadi kesalahan saat login akun dengan facebook',
                'error' => [
                    'type' => 'Kesalahan Autentikasi',
                    'details' => 'Autentikasi facebook gagal karena kredensial tidak valid atau masalah jaringan.',
                ]
            ], 400);
        }

        $facebook = Socialite::driver('facebook')->user();

        $user = User::where('email', $facebook->getEmail())->first();

        if (!$user) {
            $newUser = $this->userService->createUserWithRole([
                'username' => $facebook->getName(),
                'email' => $facebook->getEmail(),
                'password' => Hash::make($this->generateRandomPassword(15)),
            ], 'user');

            $token = $user->createToken('api_token')->plainTextToken;

            $this->facebookService->insertData([
                'access_token' => $token,
                'pengguna_id' => $newUser->id,
                'tanggal_masuk' => now(),
            ]);

            return redirect($facebookRedirectUri . '/login/facebook')->with([
                "success" => true,
                "code" => 200,
                "token" => $token,
                "data" => $user,
            ], 200);
        }

        if ($user->is_banned) {
            Auth::logout();
            return response()->json([
                "success" => false,
                "code" => 500,
                "error" => 'Akun Anda telah diblokir. Silakan hubungi administrator.',
            ], 500);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        $facebook = Facebook::where('user_id', $user->id)->first();

        if ($facebook) {
            $this->facebookService->update($facebook->id, [
                'access_token' => $token,
                'login_date' => now(),
            ]);
        }

        return redirect($facebookRedirectUri . '/login/facebook')->with([
            "success" => true,
            "code" => 200,
            "token" => $token,
            "data" => $user,
        ], 200);
    }

    private function generateRandomPassword($length = 15)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+-=';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $password;
    }

    public function register(Request $request)
    {
        $dataUser = $request->only([
            'username',
            'email',
            'password',
        ]);
        $role = strtolower($request->role);

        try {
            $result = [
                'success' => true,
                'code' => 200,
            ];

            $result['dataUser'] = $this->userService->createUserWithRole($dataUser, $role);
        } catch (Exception $e) {
            $this->userService->deleteUserData($result['dataUser']->id);

            $result = [
                'success' => false,
                'code' => 500,
                'error' => $e->getMessage(),
            ];
        }

        return response()->json($result, $result['code']);
    }

    public function sendOtp(Request $request)
    {
        $email = $request->only(['email']);

        try {
            $result = $this->otpService->sendOtp($email);

            if (in_array($result['code'], [400, 404, 429])) {
                $result = [
                    'success' => false,
                    'code' => $result['code'],
                    'error' => $result['error'],
                ];
            } else if ($result['code'] == 200) {
                $user = User::where('email', $request->email)->first();
                $token = $user->createToken('auth_token')->plainTextToken;

                $result = [
                    'success' => true,
                    'code' => 200,
                    'message' => $result['message'],
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'otp' => $result['otp'],
                    'expires_at' => $result['expires_at'],
                ];
            }
        } catch (Exception $e) {
            $result = [
                'success' => false,
                'code' => 500,
                'error' => $e->getMessage() . ', Kesalahan dalam Mengirim OTP.'
            ];
        }

        return response()->json($result, $result['code']);
    }

    public function sendOtpNewEmail(Request $request)
    {
        $email = $request->only(['email']);

        try {
            $result = $this->otpService->sendOtpNewEmail($email);

            if (in_array($result['code'], [400, 404, 429, 500])) {
                $result = [
                    'success' => false,
                    'code' => $result['code'],
                    'error' => $result['error'],
                ];
            } else if ($result['code'] == 200) {
                $result = [
                    'success' => true,
                    'code' => 200,
                    'message' => $result['message'],
                    'otp' => $result['otp'],
                    'expires_at' => $result['expires_at'],
                ];
            }
        } catch (Exception $e) {
            $result = [
                'success' => false,
                'code' => 500,
                'error' => $e->getMessage() . ', Kesalahan dalam Mengirim OTP.'
            ];
        }

        return response()->json($result, $result['code']);
    }

    public function verifyOtp(Request $request)
    {
        try {
            $user = Auth::user();
            $result = $this->otpService->verifyOtp($user->email, $request->otp);

            if ($result['code'] == 400) {
                $result = [
                    'success' => false,
                    'code' => $result['code'],
                    'error' => $result['error'],
                ];
            } else if ($result['code'] == 200) {
                $user = User::find($user->id);
                $token = $user->createToken('auth_token')->plainTextToken;

                $result = [
                    'success' => true,
                    'code' => $result['code'],
                    'message' => $result['message'],
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ];
            }
        } catch (Exception $e) {
            $result = [
                'success' => false,
                'code' => 500,
                'error' => $e->getMessage() . ', Kesalahan dalam Verifikasi OTP.'
            ];
        }

        return response()->json($result, $result['code']);
    }

    public function verifyOtpNewEmail(Request $request)
    {
        try {
            $result = $this->otpService->verifyOtp($request->email, $request->otp);

            if ($result['code'] == 400) {
                $result = [
                    'success' => false,
                    'code' => $result['code'],
                    'error' => $result['error'],
                ];
            } else if ($result['code'] == 200) {
                $result = [
                    'success' => true,
                    'code' => $result['code'],
                    'message' => $result['message'],
                ];
            }
        } catch (Exception $e) {
            $result = [
                'success' => false,
                'code' => 500,
                'error' => $e->getMessage() . ', Kesalahan dalam Verifikasi OTP.'
            ];
        }

        return response()->json($result, $result['code']);
    }

    public function resetPassword(Request $request)
    {
        $dataPasswordUser = $request->only([
            'password',
            'password_confirmation'
        ]);

        try {
            $user = Auth::user();
            $result = $this->otpService->resetPassword($user, $dataPasswordUser);

            $result = [
                'success' => true,
                'code' => $result['code'],
                'data' => $result['data'],
            ];
        } catch (Exception $e) {
            $result = [
                'success' => false,
                'code' => 500,
                'error' => $e->getMessage() . ', Kesalahan dalam Pembaruan Pengguna.'
            ];
        }

        return response()->json($result, $result['code']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->user('sanctum')->currentAccessToken()->delete();

        return response()->json([
            "success" => true,
            "code" => 200,
            "data" => 'berhasil. ' . 'Anda telah keluar.'
        ]);
    }
}
