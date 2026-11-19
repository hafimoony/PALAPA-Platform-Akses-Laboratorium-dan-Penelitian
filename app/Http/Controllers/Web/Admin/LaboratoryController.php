<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\Laboratory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LaboratoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Laboratory::with('campus');
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('lab_name', 'like', "%$s%")
                                       ->orWhere('faculty', 'like', "%$s%")
                                       ->orWhereHas('campus', fn($c) => $c->where('campus_name', 'like', "%$s%")));
        }
        $laboratories = $query->orderBy('lab_name')->paginate(10)->withQueryString();
        return view('staff.laboratories.index', compact('laboratories'));
    }

    public function create()
    {
        $campuses = Campus::orderBy('campus_name')->get();
        return view('staff.laboratories.create', compact('campuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'campus_id'              => 'required|exists:campuses,campus_id',
            'faculty'                => 'required|string|max:100',
            'lab_name'               => 'required|string|max:100',
            'category'               => 'required|string|max:30',
            'capacity'               => 'required|integer|min:1',
            'base_price_per_session' => 'required|numeric|min:0',
            'address_description'    => 'nullable|string',
        ]);
        $validated['lab_id'] = 'LAB-' . Str::upper(Str::random(8));
        $validated['is_active'] = $request->boolean('is_active', true);
        Laboratory::create($validated);

        return redirect()->route('staff.laboratories.index')->with('success', 'Laboratorium berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $laboratory = Laboratory::findOrFail($id);
        $campuses = Campus::orderBy('campus_name')->get();
        return view('staff.laboratories.edit', compact('laboratory', 'campuses'));
    }

    public function update(Request $request, string $id)
    {
        $laboratory = Laboratory::findOrFail($id);
        $validated = $request->validate([
            'campus_id'              => 'required|exists:campuses,campus_id',
            'faculty'                => 'required|string|max:100',
            'lab_name'               => 'required|string|max:100',
            'category'               => 'required|string|max:30',
            'capacity'               => 'required|integer|min:1',
            'base_price_per_session' => 'required|numeric|min:0',
            'address_description'    => 'nullable|string',
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $laboratory->update($validated);

        return redirect()->route('staff.laboratories.index')->with('success', 'Laboratorium berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $laboratory = Laboratory::withCount(['bookings', 'equipments'])->findOrFail($id);
        if ($laboratory->bookings_count > 0 || $laboratory->equipments_count > 0) {
            return back()->with('error', 'Laboratorium tidak dapat dihapus karena sudah terhubung dengan data alat atau transaksi reservasi.');
        }
        $laboratory->delete();
        return redirect()->route('staff.laboratories.index')->with('success', 'Laboratorium berhasil dihapus.');
    }
}
