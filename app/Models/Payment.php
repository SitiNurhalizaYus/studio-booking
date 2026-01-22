<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'status'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    protected static function booted()
    {
        static::saved(function ($payment) {

            if ($payment->status === 'paid') {
                $booking = $payment->booking;

                if ($booking && $booking->status === 'pending') {
                    $booking->update([
                        'status' => 'confirmed'
                    ]);
                }
            }
        });
    }
}
