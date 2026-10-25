<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Laboratory;

class BookingController extends Controller
{
    // GET /bookings/step1/{lab_id}  -> Formulir Reservasi (Tahap 1: Instansi & Jadwal)
    public function step1($lab_id)
    {
        $lab = Laboratory::with('equipments')
            ->where('is_active', true)
            ->findOrFail($lab_id);

        return view('client.booking.step1', compact('lab'));
    }
}
