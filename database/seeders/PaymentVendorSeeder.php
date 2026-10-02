<?php

namespace Database\Seeders;

use App\Models\PaymentVendor;
use Illuminate\Database\Seeder;

class PaymentVendorSeeder extends Seeder
{
    public function run(): void
    {
        PaymentVendor::updateOrCreate(
            ['vendor_id' => 'VND-001'],
            [
                'vendor_code' => 'QRIS_INSTANT',
                'vendor_name' => 'QRIS Mandiri / BCA',
                'vendor_type' => 'qris_instant',
                'account_number_or_merchant_id' => 'ID102030405060',
                'admin_fee' => 0.00,
                'is_active' => true
            ]
        );
    }
}
