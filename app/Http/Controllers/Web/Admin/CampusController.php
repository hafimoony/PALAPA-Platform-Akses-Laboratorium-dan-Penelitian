<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CampusController extends Controller
{
    public function index(Request $request)
    {
        $query = Campus::withCount('laboratories');
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('campus_name', 'like', "%$s%")
                                       ->orWhere('institution_name', 'like', "%$s%"));
        }
        $campuses = $query->orderBy('campus_name')->paginate(10)->withQueryString();
        return view('staff.campuses.index', compact('campuses'));
    }

    public function create()
    {
        return view('staff.campuses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'campus_name'      => 'required|string|max:100|unique:campuses,campus_name',
            'institution_name' => 'required|string|max:150',
            'address'          => 'nullable|string',
        ]);
        $validated['campus_id'] = 'CMP-' . Str::upper(Str::random(8));
        Campus::create($validated);

        return redirect()->route('staff.campuses.index')->with('success', 'Data kampus berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $campus = Campus::findOrFail($id);
        return view('staff.campuses.edit', compact('campus'));
    }

    public function update(Request $request, string $id)
    {
        $campus = Campus::findOrFail($id);
        $validated = $request->validate([
            'campus_name'      => 'required|string|max:100|unique:campuses,campus_name,' . $id . ',campus_id',
            'institution_name' => 'required|string|max:150',
            'address'          => 'nullable|string',
        ]);
        $campus->update($validated);

        return redirect()->route('staff.campuses.index')->with('success', 'Data kampus berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $campus = Campus::withCount('laboratories')->findOrFail($id);
        if ($campus->laboratories_count > 0) {
            return back()->with('error', 'Kampus tidak dapat dihapus karena masih memiliki data laboratorium.');
        }
        $campus->delete();
        return redirect()->route('staff.campuses.index')->with('success', 'Data kampus berhasil dihapus.');
    }
}
