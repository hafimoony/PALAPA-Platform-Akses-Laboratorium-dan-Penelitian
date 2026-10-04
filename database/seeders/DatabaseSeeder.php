<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Matikan pengecekan Foreign Key sementara agar tidak error saat migrate:fresh
        Schema::disableForeignKeyConstraints();

        // Panggil seeder 50 laboratorium yang sudah dibuat
        $this->call([
            LaboratorySeeder::class,
        ]);

        // Nyalakan kembali pengecekan Foreign Key
        Schema::enableForeignKeyConstraints();
    }
}
