<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];


    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function histories()
    {
        return $this->hasMany(PaymentHistory::class);
    }
}
