<?php

if (!function_exists('bookingStatusLabel')) {
    function bookingStatusLabel(string $status): string
    {
        return [
            'pending'          => 'Menunggu Pembayaran',
            'waiting_payment'  => 'DP Dibayar',
            'confirmed'        => 'Butuh Konfirmasi',
            'completed'        => 'Selesai',
            'cancelled'        => 'Dibatalkan',
        ][$status] ?? '-';
    }
}

if (!function_exists('paymentStatusLabel')) {
    function paymentStatusLabel(string $status): string
    {
        return [
            'pending' => 'Belum Dibayar',
            'dp'      => 'DP',
            'paid'    => 'Lunas',
        ][$status] ?? '-';
    }
}
