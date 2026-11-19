<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\LabEquipment;
use App\Models\Laboratory;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Roles (Wajib untuk RBAC)
        $roleStaff = Role::firstOrCreate(['id' => 1], ['nama_role' => 'Staff Laboratorium']);
        $roleClient = Role::firstOrCreate(['id' => 2], ['nama_role' => 'Client Penyewa']);

        // 2. Seed Akun Uji Coba Role 1 (Staff / Laboran)
        $staff = User::firstOrCreate(
            ['email' => 'staff@palapa.ac.id'],
            [
                'user_id'       => 'USR-STAFF001',
                'name'          => 'Budi Santoso, S.Si. (Staff Lab)',
                'password_hash' => Hash::make('password123'),
                'phone_number'  => '081234567890',
                'role'          => 'staff',
            ]
        );
        UserRole::firstOrCreate(['user_id' => $staff->user_id], ['role_id' => $roleStaff->id]);

        // 3. Seed Akun Uji Coba Role 2 (Client / Penyewa)
        $client = User::firstOrCreate(
            ['email' => 'client@instansi.or.id'],
            [
                'user_id'       => 'USR-CLIENT01',
                'name'          => 'Dr. Siti Rahmawati (Peneliti)',
                'password_hash' => Hash::make('password123'),
                'phone_number'  => '089876543210',
                'role'          => 'client',
            ]
        );
        UserRole::firstOrCreate(['user_id' => $client->user_id], ['role_id' => $roleClient->id]);

        // 4. Jalankan Seeder Kampus & Laboratorium bawaan jika ada
        if (class_exists(CampusSeeder::class)) {
            $this->call(CampusSeeder::class);
        }
        if (class_exists(LaboratorySeeder::class)) {
            $this->call(LaboratorySeeder::class);
        }

        // Fallback jika tabel campuses/laboratories masih kosong
        if (Campus::count() === 0) {
            $campus = Campus::create([
                'campus_id'        => 'CMP-UNAIR01',
                'campus_name'      => 'Kampus C Mulyorejo',
                'institution_name' => 'Universitas Airlangga',
                'address'          => 'Jl. Dr. Ir. H. Soekarno, Mulyorejo, Surabaya',
            ]);

            Laboratory::create([
                'lab_id'                 => 'LAB-KIMIA01',
                'campus_id'              => $campus->campus_id,
                'faculty'                => 'Fakultas Sains dan Teknologi',
                'lab_name'               => 'Laboratorium Kimia Analitik Terpadu',
                'category'               => 'Kimia',
                'capacity'               => 25,
                'address_description'    => 'Gedung FST Lantai 2 Sayap Timur',
                'base_price_per_session' => 500000,
                'is_active'              => true,
            ]);
        }

        // 5. Seed Alat & Instrumen pada setiap Laboratorium (M3)
        foreach (Laboratory::all() as $index => $lab) {
            LabEquipment::firstOrCreate(
                ['serial_number' => 'SN-SPEC-' . ($index + 1)],
                [
                    'equipment_id'          => 'EQP-' . Str::upper(Str::random(8)),
                    'lab_id'                => $lab->lab_id,
                    'equipment_name'        => 'Spektrofotometer UV-Vis',
                    'brand_model'           => 'Shimadzu UV-1900i',
                    'quantity_total'        => 3,
                    'rental_price_per_unit' => 150000,
                    'status'                => 'available',
                ]
            );

            LabEquipment::firstOrCreate(
                ['serial_number' => 'SN-MICR-' . ($index + 1)],
                [
                    'equipment_id'          => 'EQP-' . Str::upper(Str::random(8)),
                    'lab_id'                => $lab->lab_id,
                    'equipment_name'        => 'Mikroskop Binokuler Digital',
                    'brand_model'           => 'Olympus CX23',
                    'quantity_total'        => 10,
                    'rental_price_per_unit' => 50000,
                    'status'                => 'available',
                ]
            );
        }

        // 6. Pastikan tabel payment_vendors minimal memiliki 1 kanal aktif untuk checkout
        if (DB::getSchemaBuilder()->hasTable('payment_vendors') && DB::table('payment_vendors')->count() === 0) {
            DB::table('payment_vendors')->insert([
                'vendor_id'                     => 'VND-001',
                'vendor_code'                   => 'BNI_VA',
                'vendor_name'                   => 'BNI Virtual Account',
                'vendor_type'                   => 'virtual_account',
                'account_number_or_merchant_id' => '880812345678',
                'admin_fee'                     => 5000,
                'is_active'                     => 1,
            ]);
        }
    }
}
