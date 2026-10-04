<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laboratory;
use Illuminate\Http\Request;

class LaboratoryController extends Controller
{
    // API: Daftar Laboratorium
    public function index(Request $request)
    {
        $query = Laboratory::with('equipments')->where('is_active', true);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('lab_name', 'like', '%' . $search . '%')
                  ->orWhere('campus_name', 'like', '%' . $search . '%')
                  ->orWhere('faculty', 'like', '%' . $search . '%');
            });
        }

        $labs = $query->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar laboratorium berhasil diambil',
            'data' => $labs
        ], 200);
    }

    // API: Detail Laboratorium
    public function show($id)
    {
        $lab = Laboratory::with('equipments')->find($id);

        if (!$lab) {
            return response()->json([
                'status' => 'error',
                'message' => 'Laboratorium tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Detail laboratorium berhasil diambil',
            'data' => $lab
        ], 200);
    }
}
