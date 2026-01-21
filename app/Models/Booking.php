<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'customer_id',
        'booking_date',
        'start_time',
        'end_time',
        'package',
        'payment_status'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

