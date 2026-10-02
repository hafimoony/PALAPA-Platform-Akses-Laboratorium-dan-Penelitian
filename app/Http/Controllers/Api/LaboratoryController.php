<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laboratory;
use Illuminate\Http\Request;

class LaboratoryController extends Controller
{
    // Mengambil daftar laboratorium terdekat / terfilter
    public function index(Request $request) {
        $query = Laboratory::with('equipments')->where('is_active', true);

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $labs = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $labs
        ]);
    }

    // Detail laboratorium lengkap beserta alatnya
    public function show($id) {
        $lab = Laboratory::with('equipments')->find($id);

        if (!$lab) {
            return response()->json(['message' => 'Laboratorium tidak ditemukan'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $lab
        ]);
    }
}
