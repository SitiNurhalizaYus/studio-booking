<?php

namespace App\Helpers;

use App\Models\Booking;
use Carbon\Carbon;

class WhatsAppMessage
{
    private static function date(Booking $booking): string
    {
        return Carbon::parse($booking->booking_date)->format('d M Y');
    }

    private static function time(Booking $booking): string
    {
        return Carbon::parse($booking->start_time)->format('H:i')
            . ' - ' .
            Carbon::parse($booking->end_time)->format('H:i');
    }

    /* =========================
       PAYMENT PENDING (AJAKAN DP)
    ========================== */
    public static function pending(Booking $booking): string
    {
        return
"Halo Kak {$booking->customer->name},

Terima kasih telah melakukan pemesanan studio foto.
Berikut detail booking Kakak:

Layanan  : {$booking->service->name}
Tanggal  : " . self::date($booking) . "
Jam      : " . self::time($booking) . "

Total biaya:
Rp " . number_format($booking->payment->total_amount, 0, ',', '.') . "

Untuk mengamankan jadwal, Kakak dapat melakukan pembayaran DP terlebih dahulu.

Transfer Bank BCA
No. Rekening: 1234567890
Atas Nama: Studio Foto Queen

Atau QRIS:
https://contoh-qris-link.com

Setelah DP kami terima, jadwal akan langsung kami konfirmasi.
Terima kasih.";
    }

    /* =========================
       DP DITERIMA
    ========================== */
    public static function dp(Booking $booking): string
    {
        return
"Halo Kak {$booking->customer->name},

Terima kasih, pembayaran DP untuk booking studio foto Kakak telah kami terima.

Detail booking:
Layanan  : {$booking->service->name}
Tanggal  : " . self::date($booking) . "
Jam      : " . self::time($booking) . "

Sisa pembayaran:
Rp " . number_format($booking->payment->remaining_amount, 0, ',', '.') . "

Pelunasan dapat dilakukan sebelum hari pelaksanaan sesi foto.
Terima kasih atas kepercayaannya.";
    }

    /* =========================
       LUNAS
    ========================== */
    public static function paid(Booking $booking): string
    {
        return
"Halo Kak {$booking->customer->name},

Kami informasikan bahwa pembayaran booking studio foto Kakak telah LUNAS.

Jadwal yang telah dikonfirmasi:
Layanan  : {$booking->service->name}
Tanggal  : " . self::date($booking) . "
Jam      : " . self::time($booking) . "

Mohon hadir tepat waktu sesuai jadwal.
Kami tunggu kehadiran Kakak di studio.
Terima kasih.";
    }

    /* =========================
       REMINDER
    ========================== */
    public static function reminder(Booking $booking): string
    {
        return
"Halo Kak {$booking->customer->name},

Kami mengingatkan kembali jadwal sesi foto Kakak:

Layanan  : {$booking->service->name}
Tanggal  : " . self::date($booking) . "
Jam      : " . self::time($booking) . "

Mohon hadir tepat waktu agar sesi foto berjalan maksimal.
Terima kasih.";
    }

    /* =========================
       SELESAI / CLOSING
    ========================== */
    public static function completed(Booking $booking): string
    {
        return
"Halo Kak {$booking->customer->name},

Terima kasih telah menggunakan layanan studio foto kami.
Semoga hasil foto sesuai dengan harapan Kakak.

Kami dengan senang hati menunggu booking berikutnya.
Sampai jumpa di sesi berikutnya.";
    }
}
