<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $payment->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .container {
            width: 800px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        th {
            background: #f3f3f3;
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .status {
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 11px;
        }

        .paid {
            background: #d1fae5;
            color: #065f46;
        }

        .dp {
            background: #ede9fe;
            color: #5b21b6;
        }

        .pending {
            background: #ffedd5;
            color: #9a3412;
        }
    </style>
</head>

<body onload="window.print()">

    <div class="container">

        {{-- HEADER --}}
        <div class="header">
            <div>
                <div class="title">INVOICE PEMBAYARAN</div>
                <div>No Invoice: <strong>{{ $payment->invoice_number }}</strong></div>
                <div>Tanggal: {{ $payment->created_at->format('d M Y') }}</div>
            </div>

            <div>
                <span class="status {{ $payment->status }}">
                    {{ strtoupper($payment->status) }}
                </span>
            </div>
        </div>

        {{-- CUSTOMER --}}
        <table>
            <tr>
                <th width="30%">Nama Pelanggan</th>
                <td>{{ $payment->booking->customer->name }}</td>
            </tr>
            <tr>
                <th>No. Telepon</th>
                <td>{{ $payment->booking->customer->phone }}</td>
            </tr>
            <tr>
                <th>Tanggal Booking</th>
                <td>{{ \Carbon\Carbon::parse($payment->booking->booking_date)->format('d M Y') }}</td>
            </tr>
            <tr>
                <th>Jam</th>
                <td>{{ $payment->booking->start_time }} - {{ $payment->booking->end_time }}</td>
            </tr>
        </table>

        {{-- DETAIL LAYANAN STUDIO --}}
        <h3 style="margin-top:20px;">Detail Layanan Studio Foto</h3>

        <table>
            <thead>
                <tr>
                    <th>Layanan</th>
                    <th>Durasi</th>
                    <th>Deskripsi</th>
                    <th class="right">Harga</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $payment->booking->service->name }}</td>
                    <td>{{ $payment->booking->service->duration }} Jam</td>
                    <td>{{ $payment->booking->service->description ?? 'Sesi foto studio profesional' }}</td>
                    <td class="right">
                        Rp {{ number_format($payment->total_amount, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- RINGKASAN PEMBAYARAN --}}
        <h3 style="margin-top:20px;">Ringkasan Pembayaran</h3>

        <table>
            <tr>
                <th>Total Tagihan</th>
                <td class="right">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Sudah Dibayar</th>
                <td class="right">Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Sisa Pembayaran</th>
                <td class="right">Rp {{ number_format($payment->remaining_amount, 0, ',', '.') }}</td>
            </tr>
        </table>

        {{-- RIWAYAT --}}
        <h3 style="margin-top:20px;">Riwayat Pembayaran</h3>

        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th class="right">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payment->histories as $history)
                    <tr>
                        <td>{{ $history->created_at->format('d M Y H:i') }}</td>
                        <td>{{ strtoupper($history->type) }}</td>
                        <td class="right">
                            Rp {{ number_format($history->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p style="margin-top:30px; font-size:11px;">
            Invoice ini sah dan diterbitkan oleh sistem Studio Foto.
        </p>

    </div>

</body>

</html>
