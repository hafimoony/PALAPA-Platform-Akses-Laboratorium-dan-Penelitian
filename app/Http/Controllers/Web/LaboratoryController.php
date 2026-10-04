<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Laboratory;
use Illuminate\Http\Request;

class LaboratoryController extends Controller
{
    public function beranda()
    {
        $labs = Laboratory::where('is_active', true)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('client.beranda', compact('labs'));
    }

    // Katalog Lengkap dengan Filter & Pagination
    public function index(Request $request)
    {
        $query = Laboratory::where('is_active', true);

        // Filter Pencarian (Nama Lab / Kampus)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('lab_name', 'like', '%' . $search . '%')
                  ->orWhere('campus_name', 'like', '%' . $search . '%')
                  ->orWhere('faculty', 'like', '%' . $search . '%');
            });
        }

        // Filter Kategori Riset
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Pagination 9 items per halaman
        $labs = $query->paginate(9)->withQueryString();

        return view('client.labs.index', compact('labs'));
    }

    // Detail Laboratorium
    public function show($id)
    {
        $lab = Laboratory::with('equipments')->findOrFail($id);

        return view('client.labs.show', compact('lab'));
    }
}
