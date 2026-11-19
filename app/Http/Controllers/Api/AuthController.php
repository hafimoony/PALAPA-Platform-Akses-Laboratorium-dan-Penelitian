<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExternalProfile;
use App\Models\User;
use App\Models\UserRole;
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

            // Simpan ke tabel user_roles (1 = staff/admin, 2 = client)
            UserRole::create([
                'user_id' => $user->user_id,
                'role_id' => in_array($role, ['staff', 'admin'], true) ? 1 : 2,
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

        Auth::login($user);

        // Simpan user_role ke session untuk Middleware RBAC
        if (!$request->expectsJson()) {
            $request->session()->regenerate();
        }
        $ur = UserRole::where('user_id', $user->user_id)->first();
        $roleId = $ur ? (int) $ur->role_id : (in_array($user->role, ['staff', 'admin'], true) ? 1 : 2);
        session(['user_role' => $roleId]);

        $token = $user->createToken('auth_token')->plainTextToken;

        if (!$request->expectsJson()) {
            if ($roleId === 1) {
                return redirect()->intended('/staff/dashboard')->with('success', 'Selamat datang, Staf Laboratorium!');
            }
            return redirect()->intended('/')->with('success', 'Berhasil login!');
        }

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
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

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

    // 5. LOGIN GOOGLE
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'user_id' => 'USR-' . strtoupper(Str::random(10)),
                    'name' => $googleUser->getName(),
                    'password_hash' => Hash::make(Str::random(16)),
                    'role' => 'client',
                ]
            );

            UserRole::firstOrCreate(
                ['user_id' => $user->user_id],
                ['role_id' => 2]
            );

            Auth::login($user);
            session(['user_role' => 2]);

            return redirect()->route('home')->with('success', 'Berhasil masuk dengan akun Google!');
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['msg' => 'Gagal masuk menggunakan Google. Silakan coba lagi.']);
        }
    }
}
