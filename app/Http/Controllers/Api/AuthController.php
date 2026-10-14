<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExternalProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // 1. REGISTER
    public function register(Request $request)
    {
        if ($request->isMethod('get')) {
            return response()->json([
                'message' => 'Endpoint ini digunakan untuk pendaftaran pengguna baru. Silakan kirimkan request dengan HTTP Method POST beserta data JSON registrasi.',
                'supported_method' => 'POST',
            ], 200);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users,email',
            'password' => 'required|string|min:8',
            'phone_number' => 'nullable|string|max:20',
            'role' => 'nullable|string|in:client,staff,admin',
            'institution_name' => 'nullable|string|max:150',
            'institution_type' => 'nullable|string|in:sekolah_sma_smk,lembaga_riset,perusahaan_swasta,komunitas_publik',
            'address' => 'nullable|string',
            'tax_number_npwp' => 'nullable|string|max:30',
        ]);

        $role = $validated['role'] ?? 'client';

        $result = DB::transaction(function () use ($validated, $role, $request) {
            $userId = 'USR-' . strtoupper(Str::random(10));

            $user = User::create([
                'user_id' => $userId,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password_hash' => Hash::make($validated['password']),
                'phone_number' => $request->phone_number ?? null,
                'role' => $role,
            ]);

            if ($role === 'client' && !empty($validated['institution_name'])) {
                $profileId = 'PRF-' . strtoupper(Str::random(10));

                ExternalProfile::create([
                    'profile_id' => $profileId,
                    'user_id' => $user->user_id,
                    'institution_name' => $validated['institution_name'],
                    'institution_type' => $validated['institution_type'] ?? 'sekolah_sma_smk',
                    'address' => $validated['address'] ?? null,
                    'tax_number_npwp' => $validated['tax_number_npwp'] ?? null,
                ]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user' => $user->load('externalProfile'),
                'token' => $token,
            ];
        });

        if (!$request->expectsJson()) {
            // Redirect langsung ke halaman login dengan pesan sukses
            return redirect()->route('login')->with('success', 'Registrasi akun berhasil! Silakan masuk dengan email dan kata sandi Anda.');
        }

        return response()->json([
            'message' => 'User registered successfully',
            'data' => $result['user'],
            'access_token' => $result['token'],
            'token_type' => 'Bearer',
        ], 201);
    }

    // 2. LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        // Cek pengguna & kecocokan password_hash
        if (!$user || !Hash::check($request->password, $user->password_hash)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Invalid login credentials',
                ], 401);
            }
            return back()->withInput($request->only('email'))->withErrors([
                'email' => 'Email atau kata sandi yang Anda masukkan salah.',
            ]);
        }

        // Login session untuk Web Browser
        Auth::login($user);

        // Buat token Sanctum untuk API
        $token = $user->createToken('auth_token')->plainTextToken;

        // Jika request dari Form HTML Web biasa
        if (!$request->expectsJson()) {
            return redirect()->intended('/')->with('success', 'Berhasil login!');
        }

        // Jika request dari API / JSON
        return response()->json([
            'message' => 'Login successful',
            'data' => $user->load('externalProfile'),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // 3. GET PROFILE USER (ME)
    public function me(Request $request)
    {
        return response()->json([
            'message' => 'User profile retrieved successfully',
            'data' => $request->user()->load('externalProfile'),
        ]);
    }

    // 4. LOGOUT
    public function logout(Request $request)
    {
        // Logout Session Web jika ada
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        // Revoke token API jika dipanggil via Sanctum
        if ($request->user() && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        if (!$request->expectsJson()) {
            return redirect()->route('login')->with('success', 'Berhasil keluar.');
        }

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    // PAKE GOOGLE
    // 1. Redirect ke Halaman Login Google
public function redirectToGoogle()
{
    return Socialite::driver('google')->redirect();
}

// 2. Handle Callback dari Google
public function handleGoogleCallback()
{
    try {
        $googleUser = Socialite::driver('google')->user();

        // Cari user berdasarkan email atau buat user baru
        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'password' => bcrypt(Str::random(16)), // Password acak
                'role' => 'client', // Default role penyewa
            ]
        );

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Berhasil masuk dengan akun Google!');
    } catch (\Exception $e) {
        return redirect()->route('login')->withErrors(['msg' => 'Gagal masuk menggunakan Google. Silakan coba lagi.']);
    }
}
}
