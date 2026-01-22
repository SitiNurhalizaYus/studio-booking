<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Customer;
use Illuminate\Http\Request;


class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required',
            'phone'        => 'required',
            'booking_date' => 'required|date',
            'start_time'   => 'required',
            'end_time'     => 'required|after:start_time',
            'package'      => 'required',
        ]);

        // Simpan / ambil customer
        $customer = Customer::create([
            'name'  => $request->name,
            'phone'=> $request->phone,
            'email'=> $request->email,
        ]);

        // 🔥 CEK DOUBLE BOOKING
        $conflict = Booking::where('booking_date', $request->booking_date)
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors([
                'booking_time' => 'Jadwal bentrok. Silakan pilih waktu lain.'
            ]);
        }

        // simpan booking ke variabel
        $booking = Booking::create([
            'customer_id'    => $customer->id,
            'service_id'     => $request->service_id,
            'booking_date'   => $request->booking_date,
            'start_time'     => $request->start_time,
            'end_time'       => $request->end_time,
            'package'        => $request->package,
            'payment_status' => 'pending',
        ]);

        // auto payment (DP system ready)
        Payment::create([
            'booking_id'       => $booking->id,
            'total_amount'     => $booking->service->price,
            'paid_amount'      => 0,
            'remaining_amount' => $booking->service->price,
            'status'           => 'pending',
        ]);

        // return redirect()->back()->with('success', 'Booking berhasil disimpan.');
        return response()->json([
            'message' => 'Booking & payment created',
            'booking_id' => $booking->id
        ]);

    }
}
