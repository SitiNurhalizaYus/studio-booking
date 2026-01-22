<?php

if (!function_exists('buildWaMessage')) {
    function buildWaMessage(string $type, $booking): string
    {
        $c = $booking->customer;
        $s = $booking->service;
        $p = $booking->payment;

        switch ($type) {

            case 'booking_confirmation':
                return "
Halo Kak {$c->name} 😊
Terima kasih sudah melakukan booking studio foto di tempat kami ya.

📸 Paket: {$s->name}
📅 Tanggal: {$booking->booking_date}
⏰ Jam: {$booking->start_time} - {$booking->end_time}

💰 Total biaya: Rp{$p->total_amount}
Untuk mengamankan jadwal, silakan lakukan pembayaran DP terlebih dahulu.

Kalau ada yang ingin ditanyakan, jangan ragu chat kami ya 😊
Terima kasih 🙏
";

            case 'payment_update':
                if ($p->status === 'paid') {
                    return "
Halo Kak {$c->name} 😊
Pembayaran untuk booking studio sudah LUNAS ya ✔️

📸 Paket: {$s->name}
📅 Tanggal: {$booking->booking_date}
⏰ Jam: {$booking->start_time} - {$booking->end_time}

Kami tunggu kedatangannya sesuai jadwal.
Terima kasih sudah mempercayakan momen spesialnya kepada kami ✨
";
                }

                return "
Halo Kak {$c->name} 😊
Kami informasikan bahwa pembayaran DP sebesar Rp{$p->paid_amount} sudah kami terima ya.

📸 Paket: {$s->name}
📅 Tanggal: {$booking->booking_date}
⏰ Jam: {$booking->start_time} - {$booking->end_time}

💰 Total biaya: Rp{$p->total_amount}
💵 Sisa pembayaran: Rp{$p->remaining_amount}

Sisa pembayaran bisa dilunasi paling lambat sebelum hari sesi foto ya.
Terima kasih banyak 🙏
";

            case 'session_reminder':
                return "
Halo Kak {$c->name} 😊
Kami ingin mengingatkan jadwal sesi foto Kakak ya 📸

📅 Tanggal: {$booking->booking_date}
⏰ Jam: {$booking->start_time} - {$booking->end_time}

Mohon datang tepat waktu supaya sesi berjalan lancar.
Sampai jumpa 👋
";

            default:
                return '';
        }
    }
}



if (!function_exists('waLink')) {
    function waLink(string $phone, string $message): string
    {
        // normalisasi nomor
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        return 'https://wa.me/' . $phone . '?text=' . urlencode(trim($message));
    }
}
