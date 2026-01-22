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
            'phone' => $request->phone,
            'email' => $request->email,
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
            'status' => 'pending',
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
    public function index()
    {
        $activeBookings = Booking::with(['customer', 'service'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('booking_date', 'asc')
            ->get();

        $completedBookings = Booking::with(['customer', 'service'])
            ->whereIn('status', ['completed', 'cancelled'])
            ->orderBy('booking_date', 'desc')
            ->get();

        return view('bookings.index', compact(
            'activeBookings',
            'completedBookings'
        ));
    }


    public function show(Booking $booking)
    {
        return view('bookings.show', compact('booking'));
    }

    public function updatePayment(Request $request, Booking $booking)
    {
        $payment = $booking->payment;

        $payment->update([
            'paid_amount' => $request->paid_amount,
            'remaining_amount' => $payment->total_amount - $request->paid_amount,
            'status' => $request->paid_amount >= $payment->total_amount
                ? 'paid'
                : 'pending',
        ]);

        // AUTO CONFIRMED
        if ($payment->status === 'paid') {
            $booking->update([
                'status' => 'confirmed'
            ]);
        }

        return back()->with('success', 'Pembayaran diperbarui');
    }
    public function updateStatus(Request $request, Booking $booking)
    {
        // hanya boleh jika sudah confirmed
        if ($booking->status !== 'confirmed') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:completed,cancelled'
        ]);

        $booking->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status booking diperbarui');
    }
    public function complete(Booking $booking)
    {
        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'Booking belum dikonfirmasi');
        }

        $booking->update([
            'status' => 'completed'
        ]);

        return back()->with('success', 'Booking ditandai selesai');
    }
    public function invoice(Booking $booking)
    {
        $booking->load(['customer', 'service', 'payment']);
        return view('bookings.invoice', compact('booking'));
    }
}
