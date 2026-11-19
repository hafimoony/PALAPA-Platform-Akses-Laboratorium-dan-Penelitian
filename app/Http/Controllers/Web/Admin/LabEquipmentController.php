<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\LabEquipment;
use App\Models\Laboratory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LabEquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = LabEquipment::with('laboratory');
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('equipment_name', 'like', "%$s%")
                                       ->orWhere('brand_model', 'like', "%$s%")
                                       ->orWhere('serial_number', 'like', "%$s%"));
        }
        $equipments = $query->orderBy('equipment_name')->paginate(10)->withQueryString();
        return view('staff.equipments.index', compact('equipments'));
    }

    public function create()
    {
        $laboratories = Laboratory::orderBy('lab_name')->get();
        return view('staff.equipments.create', compact('laboratories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lab_id'                => 'required|exists:laboratories,lab_id',
            'equipment_name'        => 'required|string|max:100',
            'brand_model'           => 'nullable|string|max:100',
            'serial_number'         => 'nullable|string|max:100|unique:lab_equipments,serial_number',
            'quantity_total'        => 'required|integer|min:1',
            'rental_price_per_unit' => 'required|numeric|min:0',
            'status'                => 'required|in:available,maintenance,unavailable',
        ]);
        $validated['equipment_id'] = 'EQP-' . Str::upper(Str::random(8));
        LabEquipment::create($validated);

        return redirect()->route('staff.equipments.index')->with('success', 'Alat laboratorium berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $equipment = LabEquipment::findOrFail($id);
        $laboratories = Laboratory::orderBy('lab_name')->get();
        return view('staff.equipments.edit', compact('equipment', 'laboratories'));
    }

    public function update(Request $request, string $id)
    {
        $equipment = LabEquipment::findOrFail($id);
        $validated = $request->validate([
            'lab_id'                => 'required|exists:laboratories,lab_id',
            'equipment_name'        => 'required|string|max:100',
            'brand_model'           => 'nullable|string|max:100',
            'serial_number'         => 'nullable|string|max:100|unique:lab_equipments,serial_number,' . $id . ',equipment_id',
            'quantity_total'        => 'required|integer|min:1',
            'rental_price_per_unit' => 'required|numeric|min:0',
            'status'                => 'required|in:available,maintenance,unavailable',
        ]);
        $equipment->update($validated);

        return redirect()->route('staff.equipments.index')->with('success', 'Alat laboratorium berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $equipment = LabEquipment::withCount('bookingItems')->findOrFail($id);
        if ($equipment->booking_items_count > 0) {
            return back()->with('error', 'Alat tidak dapat dihapus karena sudah tercatat dalam transaksi peminjaman.');
        }
        $equipment->delete();
        return redirect()->route('staff.equipments.index')->with('success', 'Alat laboratorium berhasil dihapus.');
    }
}
