<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
     protected $fillable = [
        'payment_id',
        'amount',
        'type',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
