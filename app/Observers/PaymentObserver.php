<?php

namespace App\Observers;

use App\Models\Payment;

class PaymentObserver
{
    public function saved(Payment $payment)
    {
        $booking = $payment->booking;

        if (! $booking) return;

        switch ($payment->status) {
            case 'paid':
                if ($booking->status !== 'completed') {
                    $booking->update(['status' => 'confirmed']);
                }
                break;

            case 'cancelled':
                $booking->update(['status' => 'cancelled']);
                break;

            default:
                // pending / partial
                if ($booking->status !== 'completed') {
                    $booking->update(['status' => 'pending']);
                }
        }
    }
}
