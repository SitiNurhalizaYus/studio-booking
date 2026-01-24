<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: Arial; font-size: 12px; color:#333; }
        table { width:100%; border-collapse: collapse; margin-top:15px; }
        th, td { border:1px solid #ccc; padding:8px; }
        th { background:#f3f3f3; }
        .right { text-align:right; }
        .header { display:flex; justify-content:space-between; align-items:center; }
    </style>
</head>

<body onload="window.print()">

<div class="header">
    <div style="display:flex; gap:12px; align-items:center;">
       <img src="{{ asset('images/logo-studio.png') }}" width="80">
        <div>
            <strong>STUDIO FOTO QUEEN</strong><br>
            Laporan Keuangan Bulanan
        </div>
    </div>
    <div>
        Periode:
        {{ \Carbon\Carbon::parse($month)->format('F Y') }}
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>No Invoice</th>
            <th>Layanan</th>
            <th>Tanggal</th>
            <th class="right">Pendapatan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($payments as $payment)
            <tr>
                <td>{{ $payment->invoice_number }}</td>
                <td>{{ $payment->booking->service->name }}</td>
                <td>{{ $payment->created_at->format('d M Y') }}</td>
                <td class="right">
                    Rp {{ number_format($payment->paid_amount,0,',','.') }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" style="text-align:center;">
                    Tidak ada transaksi
                </td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3">Total Pendapatan</th>
            <th class="right">
                Rp {{ number_format($totalRevenue,0,',','.') }}
            </th>
        </tr>
    </tfoot>
</table>

</body>
</html>
