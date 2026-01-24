<?php

if (!function_exists('bookingStatusLabel')) {
    function bookingStatusLabel(string $status): string
    {
        return match ($status) {
            'pending'          => 'Menunggu Pembayaran',
            'waiting_payment'  => 'DP Dibayar',
            'confirmed'        => 'Terkonfirmasi',
            'completed'        => 'Selesai',
            'cancelled'        => 'Dibatalkan',
            default            => ucfirst($status),
        };
    }
}

if (!function_exists('paymentStatusLabel')) {
    function paymentStatusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'Menunggu Pembayaran',
            'dp'      => 'DP',
            'paid'    => 'Lunas',
            default   => ucfirst($status),
        };
    }
}
