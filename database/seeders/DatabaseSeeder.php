<?php

namespace Database\Seeders;

use App\Models\Laboratory;
use App\Models\LabEquipment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Laboratory::updateOrCreate(
            ['lab_id' => 'LAB-001'],
            [
                'campus_name' => 'Kampus C',
                'faculty' => 'Fakultas Vokasi',
                'lab_name' => 'Lab Komputer Lanjut',
                'category' => 'komputer',
                'capacity' => 30,
                'address_description' => 'Gedung Laboratorium Terpadu Lt. 2',
                'base_price_per_session' => 500000.00,
                'is_active' => true,
            ]
        );

        LabEquipment::updateOrCreate(
            ['equipment_id' => 'EQP-001'],
            [
                'lab_id' => 'LAB-001',
                'equipment_name' => 'Proyektor High-Lumens',
                'brand_model' => 'Epson EB-X500',
                'serial_number' => 'SN-EPSON-9901',
                'quantity_total' => 5,
                'rental_price_per_unit' => 50000.00,
                'status' => 'available',
            ]
        );
    }
}
