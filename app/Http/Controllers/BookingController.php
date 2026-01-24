<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Service;
use Carbon\Carbon;

class BookingController extends Controller
{
    /* ================= INVOICE ================= */



    /* ================= CREATE BOOKING ================= */

    public function store(Request $request)
    {
        // VALIDASI DASAR (TANPA after:)
        $request->validate([
            'name'         => 'required',
            'phone'        => 'required',
            'service_id'   => 'required|exists:services,id',
            'booking_date' => 'required|date',
            'start_time'   => 'required',
            'end_time'     => 'required',
        ]);

        // VALIDASI JAM (FIX ERROR)
        $start = Carbon::createFromFormat('H:i', $request->start_time);
        $end   = Carbon::createFromFormat('H:i', $request->end_time);

        if ($end->lessThanOrEqualTo($start)) {
            return back()
                ->withInput()
                ->withErrors([
                    'end_time' => 'Jam selesai harus setelah jam mulai.'
                ]);
        }

        // SIMPAN CUSTOMER
        $customer = Customer::create([
            'name'  => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        // CEK BENTROK JADWAL
        $conflict = Booking::where('booking_date', $request->booking_date)
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                    ->where('end_time', '>', $request->start_time);
            })
            ->exists();

        if ($conflict) {
            return back()
                ->withInput()
                ->withErrors([
                    'booking_time' => 'Jadwal bentrok. Silakan pilih waktu lain.'
                ]);
        }

        // SIMPAN BOOKING
        $service = Service::findOrFail($request->service_id);

        $booking = Booking::create([
            'customer_id'  => $customer->id,
            'service_id'   => $service->id,
            'package'      => $service->name, // 🔥 INI KUNCI
            'booking_date' => $request->booking_date,
            'start_time'   => $request->start_time,
            'end_time'     => $request->end_time,
            'notes'        => $request->note,
            'status'       => 'pending',
        ]);


        $booking->load('service');

        // AUTO PAYMENT
        Payment::create([
            'booking_id'       => $booking->id,
            'total_amount'     => $booking->service->price,
            'paid_amount'      => 0,
            'remaining_amount' => $booking->service->price,
            'status'           => 'pending',
        ]);



        session(['back_url' => url()->previous()]);

        return redirect()
            ->route('bookings.index')
            ->with('success', 'Booking berhasil ditambahkan');
    }

    /* ================= LIST ================= */

    public function index()
    {
        $activeBookings = Booking::with(['customer', 'service'])
            ->whereIn('status', ['pending', 'waiting_payment', 'confirmed'])
            ->orderBy('booking_date')
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

    /* ================= PAYMENT ================= */

    public function updatePayment(Request $request, Booking $booking)
    {
        $payment = $booking->payment;

        $newPaid   = $payment->paid_amount + $request->pay_now;
        $remaining = $payment->total_amount - $newPaid;

        $payment->update([
            'paid_amount'      => $newPaid,
            'remaining_amount' => max(0, $remaining),
            'status'           => $remaining <= 0 ? 'paid' : 'dp',
        ]);

        $booking->update([
            'status' => 'confirmed',
        ]);

        return back()->with('success', 'Pembayaran diperbarui');
    }

    /* ================= UPDATE STATUS ================= */

    public function updateStatus(Request $request, Booking $booking)
    {
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

    /* ================= VIEW ================= */

    public function invoice(Booking $booking)
    {
        $booking->load(['customer', 'service', 'payment']);
        return view('bookings.invoice', compact('booking'));
    }

    public function create()
    {
        $services = Service::all();
        $customers = Customer::orderBy('name')->get();

        session(['back_url' => url()->previous()]);

        return view('bookings.create', compact('services', 'customers'));
    }

    /* ================= SLOT ================= */

    public function availableSlots(Request $request)
    {
        $request->validate([
            'booking_date' => 'required|date',
            'service_id'   => 'required|exists:services,id',
            'booking_id'   => 'nullable'
        ]);

        $service = Service::findOrFail($request->service_id);

        // 🔴 GUARD WAJIB
        if (!$service->duration || $service->duration <= 0) {
            return response()->json([]);
        }

        $duration = (int) $service->duration; // MENIT

        $studioHours = [
            '09:00',
            '10:00',
            '11:00',
            '13:00',
            '14:00',
            '15:00',
            '16:00'
        ];

        $bookings = Booking::where('booking_date', $request->booking_date)
            ->when($request->booking_id, function ($q) use ($request) {
                $q->where('id', '!=', $request->booking_id);
            })
            ->get();

        $available = [];

        foreach ($studioHours as $start) {

            $startTime = \Carbon\Carbon::createFromFormat('H:i', $start);
            $endTime   = (clone $startTime)->addMinutes($duration);

            $conflict = false;

            foreach ($bookings as $booking) {
                $bookedStart = Carbon::createFromFormat('H:i:s', $booking->start_time);
                $bookedEnd   = Carbon::createFromFormat('H:i:s', $booking->end_time);

                if ($startTime < $bookedEnd && $endTime > $bookedStart) {
                    $conflict = true;
                    break;
                }
            }


            if (!$conflict) {
                $available[] = [
                    'start' => $startTime->format('H:i'),
                    'end'   => $endTime->format('H:i'),
                ];
            }
        }

        return response()->json($available);
    }


    /* ================= UPDATE BOOKING ================= */

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'service_id'   => 'required|exists:services,id',
            'booking_date' => 'required|date',
            'start_time'   => 'required',
            'end_time'     => 'required',
        ]);

        $start = Carbon::parse($request->start_time)->format('H:i:s');
        $end   = Carbon::parse($request->end_time)->format('H:i:s');

        $conflict = Booking::where('booking_date', $request->booking_date)
            ->where('id', '!=', $booking->id)
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors([
                'time' => 'Jadwal bentrok. Silakan pilih waktu lain.'
            ]);
        }

        $booking->update([
            'service_id'   => $request->service_id,
            'booking_date' => $request->booking_date,
            'start_time'   => $start,
            'end_time'     => $end,
            'notes'        => $request->notes,
        ]);

        return redirect()
            ->route('bookings.show', $booking->id)
            ->with('success', 'Booking berhasil diperbarui.');
    }

    /* ================= DELETE ================= */

    public function destroy(Booking $booking)
    {
        if ($booking->payment) {
            $booking->payment->delete();
        }

        $booking->delete();

        return redirect()
            ->route('bookings.index')
            ->with('success', 'Booking berhasil dihapus.');
    }

    public function show(Booking $booking)
    {
        $booking->load([
            'customer',
            'service',
            'payment.histories'
        ]);

        return view('bookings.show', compact('booking'));
    }


    public function edit(Booking $booking)
    {
        $services = Service::all();
        session(['back_url' => url()->previous()]);

        return view('bookings.update', compact('booking', 'services'));
    }
}
