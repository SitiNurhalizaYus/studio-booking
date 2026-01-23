<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Service;


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
        ]);

        // Simpan / ambil customer
        $customer = Customer::create([
            'name'  => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        //CEK DOUBLE BOOKING
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
            'customer_id'  => $customer->id,
            'service_id'   => $request->service_id,
            'booking_date' => $request->booking_date,
            'start_time'   => $request->start_time,
            'end_time'     => $request->end_time,
            'notes'        => $request->note,
            'status'       => 'pending',
        ]);



        // auto payment (DP system ready)
        Payment::create([
            'booking_id'       => $booking->id,
            'total_amount'     => $booking->service->price,
            'paid_amount'      => 0,
            'remaining_amount' => $booking->service->price,
            'status'           => 'pending',
        ]);

        return redirect()
            ->route('bookings.index')
            ->with('success', 'Booking berhasil ditambahkan');
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

    public function create()
    {
        $services = Service::all(); //  TANPA is_active
        $customers = Customer::orderBy('name')->get();

        return view('bookings.create', compact('services', 'customers'));
    }
    public function availableSlots(Request $request)
    {
        $request->validate([
            'booking_date' => 'required|date',
            'service_id' => 'required',
            'booking_id' => 'nullable'
        ]);

        $service = Service::findOrFail($request->service_id);
        $duration = $service->duration;

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
            $startTime = strtotime($start);
            $endTime = strtotime("+{$duration} hour", $startTime);

            $conflict = false;

            foreach ($bookings as $booking) {
                $bookedStart = strtotime($booking->start_time);
                $bookedEnd   = strtotime($booking->end_time);

                if ($startTime < $bookedEnd && $endTime > $bookedStart) {
                    $conflict = true;
                    break;
                }
            }

            if (!$conflict) {
                $available[] = [
                    'start' => date('H:i', $startTime),
                    'end'   => date('H:i', $endTime),
                ];
            }
        }

        return response()->json($available);
    }

    public function fullDates(Request $request)
    {
        $service = Service::findOrFail($request->service_id);
        $duration = $service->duration;

        $dates = Booking::select('booking_date')
            ->groupBy('booking_date')
            ->get()
            ->filter(function ($row) use ($duration) {
                $totalBooked = Booking::where('booking_date', $row->booking_date)->count();
                return $totalBooked >= 7; // 7 slot studio per hari
            })
            ->pluck('booking_date');

        return response()->json($dates);
    }
    public function edit(Booking $booking)
    {
        $services = Service::all();

        return view('bookings.update', compact('booking', 'services'));
    }

    public function update(Request $request, Booking $booking)
    {
        if (
            ($request->service_id != $booking->service_id ||
                $request->booking_date != $booking->booking_date)
            && (!$request->start_time || !$request->end_time)
        ) {
            return back()
                ->withErrors([
                    'booking_time' => 'Silakan pilih ulang jam setelah mengganti layanan atau tanggal.'
                ])
                ->withInput();
        }

        if (in_array($booking->status, ['completed', 'cancelled'])) {
            return back()->withErrors([
                'status' => 'Booking sudah selesai / dibatalkan dan tidak dapat diubah.'
            ]);
        }
        $conflict = Booking::where('booking_date', $request->booking_date)
            ->where('id', '!=', $booking->id) // PENTING: exclude dirinya sendiri
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                    ->where('end_time', '>', $request->start_time);
            })
            ->exists();

        if ($conflict) {
            return back()
                ->withInput()
                ->withErrors([
                    'booking_time' =>
                    "Jadwal bentrok pada tanggal {$request->booking_date} pukul {$request->start_time} – {$request->end_time}. Silakan pilih jam lain."
                ]);
        }

        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'service_id' => 'required',
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $booking->customer->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        $booking->update([
            'service_id' => $request->service_id,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'notes' => $request->notes,
        ]);
        return redirect()
            ->route('bookings.show', $booking->id)
            ->with('success', 'Booking berhasil diperbarui');
    }
    public function destroy(Booking $booking)
    {
        // optional: hanya boleh hapus kalau belum completed
        if ($booking->status === 'completed') {
            return back()->withErrors([
                'delete' => 'Booking yang sudah selesai tidak dapat dihapus.'
            ]);
        }

        // hapus payment dulu (jika relasi belum cascade)
        if ($booking->payment) {
            $booking->payment->delete();
        }

        $booking->delete();

        return redirect()
            ->route('bookings.index')
            ->with('success', 'Booking berhasil dihapus.');
    }
}
