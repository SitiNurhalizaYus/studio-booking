<?php

namespace App\Observers;

use App\Models\Payment;

class PaymentObserver
{
    /**
     * Jalan SETIAP payment di-save / update
     */
    public function saving(Payment $payment): void
    {
        // AUTO FIX STATUS PAYMENT
        if ($payment->remaining_amount == 0) {
            $payment->status = 'paid';
        } elseif ($payment->paid_amount > 0) {
            $payment->status = 'dp';
        } else {
            $payment->status = 'pending';
        }
    }

    /**
     * Setelah payment disimpan
     */
    public function saved(Payment $payment): void
    {
        // AUTO SYNC BOOKING
        if ($payment->booking) {
            if ($payment->remaining_amount == 0) {
                $payment->booking->updateQuietly([
                    'status' => 'confirmed',
                ]);
            } elseif ($payment->paid_amount > 0) {
                $payment->booking->updateQuietly([
                    'status' => 'waiting_payment',
                ]);
            }
        }
    }
}
