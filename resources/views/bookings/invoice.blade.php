<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Booking</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .container {
            width: 210mm;
            min-height: 297mm;
            margin: auto;
            padding: 30px;
            box-sizing: border-box;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 80px;
        }

        h1 {
            font-size: 22px;
            margin: 0;
        }

        .info {
            margin-bottom: 30px;
        }

        .info p {
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #f5f5f5;
        }

        .text-right {
            text-align: right;
        }

        .total {
            margin-top: 20px;
            width: 40%;
            float: right;
        }

        .total td {
            border: none;
            padding: 6px 0;
        }

        .footer {
            margin-top: 60px;
            text-align: center;
            font-size: 11px;
            color: #777;
        }

        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>

<body onload="window.print()">

<div class="container">

    {{-- HEADER --}}
    <div class="header">
        <div>
            <h1>INVOICE</h1>
            <p><strong>Studio Foto</strong></p>
            <p>Jl. Contoh Studio No. 123</p>
            <p>WhatsApp: 08xxxxxxxx</p>
        </div>

        {{-- <div>
            <img src="{{ asset('logo.png') }}" class="logo">
        </div> --}}
    </div>

    {{-- INFO --}}
    <div class="info">
        <p><strong>Invoice Untuk:</strong></p>
        <p>{{ $booking->customer->name }}</p>
        <p>Tanggal Booking: {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</p>
        <p>Jam: {{ $booking->start_time }} - {{ $booking->end_time }}</p>
        <p>Status Booking: {{ ucfirst($booking->status) }}</p>
    </div>

    {{-- TABLE --}}
    <table>
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th>Durasi</th>
                <th class="text-right">Harga</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $booking->service->name }}</td>
                <td>1 Jam</td>
                <td class="text-right">
                    Rp {{ number_format($booking->payment->total_amount ?? 0, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    {{-- TOTAL --}}
    <table class="total">
        <tr>
            <td>Total</td>
            <td class="text-right">
                Rp {{ number_format($booking->payment->total_amount ?? 0, 0, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td>Dibayar</td>
            <td class="text-right">
                Rp {{ number_format($booking->payment->paid_amount ?? 0, 0, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td><strong>Sisa</strong></td>
            <td class="text-right">
                <strong>
                    Rp {{ number_format($booking->payment->remaining_amount ?? 0, 0, ',', '.') }}
                </strong>
            </td>
        </tr>
    </table>

    <div style="clear: both;"></div>

    {{-- FOOTER --}}
    <div class="footer">
        Terima kasih telah menggunakan jasa Studio Foto kami 🙏 <br>
        Invoice ini sah tanpa tanda tangan.
    </div>

</div>

</body>
</html>
