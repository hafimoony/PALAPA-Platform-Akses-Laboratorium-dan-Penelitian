<?php

namespace Database\Seeders;

use App\Models\Campus;
use Illuminate\Database\Seeder;

class CampusSeeder extends Seeder
{
    public function run(): void
    {
        $campuses = [
            ['KMP-001', 'Kawasan Sains dan Teknologi Soekarno', 'BRIN'],
            ['KMP-002', 'Kampus UI Depok', 'Universitas Indonesia'],
            ['KMP-003', 'Kawasan Riset BJ Habibie Serpong', 'BRIN'],
            ['KMP-004', 'Kampus IPB Dramaga', 'IPB University'],
            ['KMP-005', 'Kampus 1 UIN Syarif Hidayatullah', 'UIN Syarif Hidayatullah Jakarta'],
            ['KMP-006', 'Kampus Utama Universitas Pancasila', 'Universitas Pancasila'],
            ['KMP-007', 'Kampus A Grogol', 'Universitas Trisakti'],
            ['KMP-008', 'Kampus Pluit', 'Unika Atma Jaya'],
            ['KMP-009', 'Kampus Klender', 'Universitas Muhammadiyah Prof. Dr. Hamka'],
            ['KMP-010', 'Kampus ITB Ganesha', 'Institut Teknologi Bandung'],
            ['KMP-011', 'Kampus Unpad Jatinangor', 'Universitas Padjadjaran'],
            ['KMP-012', 'Telkom University Dayeuhkolot', 'Telkom University'],
            ['KMP-013', 'Kawasan Riset BRIN Bandung', 'BRIN'],
            ['KMP-014', 'Kampus UGM Bulaksumur', 'Universitas Gadjah Mada'],
            ['KMP-015', 'Kampus Undip Tembalang', 'Universitas Diponegoro'],
            ['KMP-016', 'Kampus UNS Kentingan', 'Universitas Sebelas Maret'],
            ['KMP-017', 'Kampus UNY Karangmalang', 'Universitas Negeri Yogyakarta'],
            ['KMP-018', 'Kampus Terpadu UII Kaliurang', 'Universitas Islam Indonesia'],
            ['KMP-019', 'Kampus 2 UMS Pabelan', 'Universitas Muhammadiyah Surakarta'],
            ['KMP-020', 'Kampus Unsoed Purwokerto', 'Universitas Jenderal Soedirman'],
            ['KMP-021', 'Kampus C Mulyorejo', 'Universitas Airlangga'],
            ['KMP-022', 'Kampus ITS Sukolilo', 'Institut Teknologi Sepuluh Nopember'],
            ['KMP-023', 'Kampus Utama UB Malang', 'Universitas Brawijaya'],
            ['KMP-024', 'Kampus UNEJ Tegalboto', 'Universitas Jember'],
            ['KMP-025', 'Kampus Utama UM Malang', 'Universitas Negeri Malang'],
            ['KMP-026', 'Kampus UNESA Lidah Wetan', 'Universitas Negeri Surabaya'],
            ['KMP-027', 'Kampus UPN Rungkut', 'UPN Veteran Jawa Timur'],
            ['KMP-028', 'Kampus UTM Telang', 'Universitas Trunojoyo Madura'],
            ['KMP-029', 'Kampus Unand Limau Manis', 'Universitas Andalas'],
            ['KMP-030', 'Kampus USU Padang Bulan', 'Universitas Sumatera Utara'],
            ['KMP-031', 'Kampus UNRI Bina Widya', 'Universitas Riau'],
            ['KMP-032', 'Kampus Unsri Indralaya', 'Universitas Sriwijaya'],
            ['KMP-033', 'Kampus Unila Gedung Meneng', 'Universitas Lampung'],
            ['KMP-034', 'Kampus USK Darussalam', 'Universitas Syiah Kuala'],
            ['KMP-035', 'Kampus ITERA Lampung', 'Institut Teknologi Sumatera'],
            ['KMP-036', 'Kampus UNHAS Tamalanrea', 'Universitas Hasanuddin'],
            ['KMP-037', 'Kampus Unud Bukit Jimbaran', 'Universitas Udayana'],
            ['KMP-038', 'Kampus Unmul Gunung Kelua', 'Universitas Mulawarman'],
            ['KMP-039', 'Kampus ITK Balikpapan', 'Institut Teknologi Kalimantan'],
            ['KMP-040', 'Kampus ULM Banjarbaru', 'Universitas Lambung Mangkurat'],
            ['KMP-041', 'Kampus Unram Mataram', 'Universitas Mataram'],
            ['KMP-042', 'Kampus Unsrat Bahu', 'Universitas Sam Ratulangi'],
            ['KMP-043', 'Kampus Untad Tondo', 'Universitas Tadulako'],
            ['KMP-044', 'Kampus Uncen Waena', 'Universitas Cenderawasih'],
            ['KMP-045', 'Kampus Unpatti Poka', 'Universitas Pattimura'],
            ['KMP-046', 'Kampus Undana Penfui', 'Universitas Nusa Cendana'],
            ['KMP-047', 'Kawasan Riset BRIN Ancol', 'BRIN'],
            ['KMP-048', 'Kampus Unimed Willem Iskandar', 'Universitas Negeri Medan'],
            ['KMP-049', 'Kampus Unib Kandang Limun', 'Universitas Bengkulu'],
            ['KMP-050', 'Kampus Unipa Amban', 'Universitas Papua'],
        ];

        foreach ($campuses as [$id, $name, $institution]) {
            Campus::updateOrCreate(
                ['campus_id' => $id],
                [
                    'campus_name' => $name,
                    'institution_name' => $institution,
                    'address' => null,
                ]
            );
        }
    }
}
