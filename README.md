# PALAPA-Platform-Akses-Laboratorium-dan-Penelitian

## 👥 Anggota Tim Pengembang

| No | Nama | NIM |
| :-: | :--- | :--- |
| 1 | **Hafizha Qonita Azzahra** | `434251010` |
| 2 | **Ilham Irzi Tri Wahyu** | `434251015` |
| 3 | **Hibban Rabbani** | `434251009` |
| 4 | **Deva Andika Febriano** | `434251002` |

# PALAPA — Sistem Reservasi Laboratorium, Jadwal Praktikum & Peminjaman Alat

Aplikasi web berbasis **Laravel** dan **PostgreSQL** untuk mengelola penyewaan fasilitas laboratorium kampus beserta peminjaman instrumen/alat riset secara terintegrasi.

## 1. Akun Uji Coba (Seeder)

| Peran (Role) | Email | Password | Hak Akses Utama |
| :--- | :--- | :--- | :--- |
| **Role 1 — Staff Laboratorium** | `staff@palapa.ac.id` | `password123` | Dashboard Staff, CRUD 3 Data Master, Verifikasi Booking, Check-In Entry Pass, Insiden & Refund, Laporan PNBP |
| **Role 2 — Client / Penyewa** | `client@instansi.or.id` | `password123` | Pengajuan Reservasi Lab & Alat (Multi-step), Pembayaran Invoice, Riwayat Reservasi |
| **Role 3 — Publik / Guest** | *(Tanpa Login)* | — | Melihat Beranda, Katalog Laboratorium, Detail Lab, dan Pusat Bantuan |

## 2. Arsitektur Fitur & Pemenuhan Kriteria UAS

* **Role-Based Access Control (RBAC):** Pembagian 3 tingkat akses (`Guest`, `Role 1: Staff`, `Role 2: Client`) menggunakan tabel `roles` & `user_roles` serta middleware kustom `CheckRole` (`role:1`, `role:2`).
* **3 Data Master Saling Berelasi (CRUD):**
  1. **Kampus (`campuses`)** — Master induk lokasi perguruan tinggi.
  2. **Laboratorium (`laboratories`)** — Terelasi ke `campuses` (tarif sesi, kapasitas, status aktif).
  3. **Alat & Instrumen (`lab_equipments`)** — Terelasi ke `laboratories` (stok, harga sewa per unit, serial number).
  * *Dilengkapi pencarian, pagination, validasi server-side, dan proteksi penghapusan apabila data sudah terhubung dengan transaksi.*
* **5 Alur Transaksi Terintegrasi:**
  1. **T1 — Pengajuan Reservasi & Peminjaman Alat (Header–Detail):** Menggunakan `DB::transaction()` dan `lockForUpdate()` untuk mencegah bentrok jadwal (`BaseTransaction` $\rightarrow$ `Booking` $\rightarrow$ `BookingEquipmentItem` + `Invoice`).
  2. **T2 — Verifikasi & Persetujuan Reservasi (Alur 2 Role):** Staff meninjau pengajuan Client (`Setujui` otomatis menerbitkan `LabEntryPass`, atau `Tolak` dengan alasan).
  3. **T3 — Pembayaran Invoice:** Simulasi verifikasi pembayaran yang memperbarui status tagihan menjadi `paid_successful` dan reservasi menjadi `paid_confirmed`.
  4. **T4 — Check-In Entry Pass:** Verifikasi kehadiran peserta di laboratorium dan pembaruan status kunjungan menjadi `completed`.
  5. **T5 — Pencatatan Insiden Kerusakan & Pengembalian Deposit:** Pencatatan kerusakan alat (otomatis mengubah status alat menjadi `maintenance`) dan kalkulasi otomatis pengembalian uang jaminan (`DepositRefund`).
* **Laporan Rekapitulasi (Query Builder):** Rekap jumlah reservasi dan total pendapatan lunas per laboratorium menggunakan `DB::table()` dengan `join()`, `groupBy()`, `COUNT()`, dan `SUM()`.

## 3. Cara Menjalankan Proyek

```bash
composer install
cp .env.example .env
php artisan key:generate
# Sesuaikan konfigurasi DB_CONNECTION=pgsql dan kredensial database di .env
php artisan migrate
php artisan db:seed
php artisan serve
