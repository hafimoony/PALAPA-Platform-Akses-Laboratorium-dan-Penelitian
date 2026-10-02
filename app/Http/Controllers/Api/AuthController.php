<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExternalProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // 1. REGISTER
    public function register(Request $request)
{
    // Jika diakses via browser / Method GET, berikan respon petunjuk singkat
    if ($request->isMethod('get')) {
        return response()->json([
            'message' => 'Endpoint ini digunakan untuk pendaftaran pengguna baru. Silakan kirimkan request dengan HTTP Method POST beserta data JSON registrasi.',
            'supported_method' => 'POST',
        ], 200);
    }

    // Jika Method POST, jalankan proses registrasi seperti biasa
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
            return response()->json([
                'message' => 'Invalid login credentials',
            ], 401);
        }

        // Hapus token lama jika ingin membatasi 1 device saja (opsional)
        // $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

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
        // Revoke token yang sedang digunakan
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}
