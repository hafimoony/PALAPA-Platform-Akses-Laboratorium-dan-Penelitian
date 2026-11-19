<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $booking->invoice->invoice_number ?? $booking->booking_code }} - PALAPA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #f8fafc; font-family: 'Poppins', sans-serif; color: #0f172a; }
        .container { max-width: 820px; margin: 30px auto; padding: 0 20px; }
        .invoice-box { background: #fff; border: 1px solid #E2E8F0; border-radius: 16px; padding: 32px; box-shadow: 0 6px 20px rgba(0,0,0,0.04); }
        .top { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #EBF3FF; padding-bottom: 20px; margin-bottom: 20px; }
        .title { font-size: 22px; font-weight: 800; color: #091F5B; }
        .badge { padding: 5px 12px; border-radius: 999px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        .paid { background: #DCFCE7; color: #166534; }
        .unpaid { background: #FEF3C7; color: #92400E; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 13px; }
        th { background: #EBF3FF; color: #091F5B; text-align: left; padding: 10px 12px; font-weight: 700; }
        td { padding: 10px 12px; border-bottom: 1px solid #F1F5F9; }
        .text-right { text-align: right; }
        .total-row td { font-weight: 800; font-size: 15px; background: #F8FAFC; color: #091F5B; }
        .btn { padding: 8px 16px; border-radius: 8px; font-size: 12px; font-weight: 700; text-decoration: none; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
        .btn-outline { background: #fff; color: #334EAC; border: 1px solid #334EAC; }
        .btn-primary { background: #334EAC; color: #fff; }
        @media print { .no-print { display: none; } body { background: #fff; } .invoice-box { border: none; box-shadow: none; } }
    </style>
</head>
<body>
<div class="container">
    <div class="no-print" style="display:flex; justify-content:space-between; margin-bottom:14px;">
        <a href="{{ route('riwayat') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat</a>
        <button onclick="window.print()" class="btn btn-primary"><i class="fa-solid fa-print"></i> Cetak Invoice</button>
    </div>

    <div class="invoice-box">
        <div class="top">
            <div>
                <div class="title">INVOICE RESERVASI PNBP</div>
                <div style="font-size:12px; color:#64748b; margin-top:4px;">
                    No. Tagihan: <strong>{{ $booking->invoice->invoice_number ?? '-' }}</strong> | Kode Booking: <strong>{{ $booking->booking_code }}</strong>
                </div>
            </div>
            <div style="text-align:right;">
                @php $isPaid = ($booking->invoice->payment_status ?? '') === 'paid_successful'; @endphp
                <span class="badge {{ $isPaid ? 'paid' : 'unpaid' }}">
                    {{ $isPaid ? 'LUNAS (PAID)' : 'MENUNGGU PEMBAYARAN' }}
                </span>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; font-size:12px;">
            <div>
                <div style="color:#64748b;">Ditagihkan Kepada:</div>
                <div style="font-weight:700; font-size:14px; margin-top:2px;">{{ $booking->baseTransaction->user->name ?? auth()->user()->name }}</div>
                <div>{{ $booking->baseTransaction->user->email ?? auth()->user()->email }}</div>
            </div>
            <div style="text-align:right;">
                <div style="color:#64748b;">Fasilitas Laboratorium:</div>
                <div style="font-weight:700; font-size:14px; margin-top:2px;">{{ $booking->laboratory->lab_name ?? '-' }}</div>
                <div>{{ $booking->laboratory->campus->campus_name ?? '' }} • {{ \Carbon\Carbon::parse($booking->reservation_date)->format('d M Y') }} ({{ $booking->session_time }})</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Deskripsi Layanan / Instrumen</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Sewa Ruang {{ $booking->laboratory->lab_name ?? 'Laboratorium' }} (1 Sesi)</td>
                    <td class="text-right">Rp {{ number_format($booking->invoice->lab_rental_fee ?? 0, 0, ',', '.') }}</td>
                </tr>
                @foreach ($booking->equipmentItems as $item)
                    <tr>
                        <td>Peminjaman Alat: {{ $item->equipment->equipment_name ?? $item->equipment_id }} ({{ $item->quantity }} unit)</td>
                        <td class="text-right">Rp {{ number_format($item->subtotal_price ?? 0, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td>Biaya Asisten Laboran / Teknis</td>
                    <td class="text-right">Rp {{ number_format($booking->invoice->technician_fee ?? 0, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Deposit Jaminan Keamanan Alat (Refundable)</td>
                    <td class="text-right">Rp {{ number_format($booking->invoice->security_deposit_fee ?? 0, 0, ',', '.') }}</td>
                </tr>
                @if (($booking->invoice->discount_amount ?? 0) > 0)
                    <tr>
                        <td style="color:#059669;">Diskon Sivitas Akademika</td>
                        <td class="text-right" style="color:#059669;">- Rp {{ number_format($booking->invoice->discount_amount, 0, ',', '.') }}</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td>TOTAL TAGIHAN PEMBAYARAN</td>
                    <td class="text-right">Rp {{ number_format($booking->invoice->grand_total ?? 0, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
