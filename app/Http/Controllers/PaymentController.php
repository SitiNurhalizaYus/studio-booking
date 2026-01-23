<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['booking.customer', 'booking.service'])
            ->latest()
            ->get();

        return view('payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        session(['back_url' => url()->previous()]);
        $payment->load(['booking.customer', 'booking.service']);
        return view('payments.show', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate(
            [
                'pay_now' => 'required|numeric',
            ],
            [
                'pay_now.required' => 'Nominal pembayaran wajib diisi',
                'pay_now.numeric'  => 'Nominal pembayaran harus berupa angka',
            ]
        );

        $payNow  = (int) $request->pay_now;
        $sisa    = $payment->remaining_amount;
        $minimal = 100000;

        // ATURAN MINIMAL BAYAR
        if ($sisa >= $minimal && $payNow < $minimal) {
            return back()->withErrors([
                'pay_now' => 'Minimal pembayaran Rp ' . number_format($minimal, 0, ',', '.')
            ]);
        }

        // TOLAK bayar lebih dari sisa
        if ($payNow > $sisa) {
            return back()->withErrors([
                'pay_now' => 'Nominal melebihi sisa pembayaran. Silakan masukkan sesuai sisa.'
            ]);
        }


        $newPaid   = $payment->paid_amount + $payNow;
        $remaining = $payment->total_amount - $newPaid;

        // Status payment
        if ($remaining === 0) {
            $paymentStatus = 'paid';
        } elseif ($newPaid > 0) {
            $paymentStatus = 'dp';
        } else {
            $paymentStatus = 'pending';
        }

        $payment->update([
            'paid_amount'      => $newPaid,
            'remaining_amount' => $remaining,
            'status'           => $paymentStatus,
        ]);

        // SIMPAN RIWAYAT
        $payment->histories()->create([
            'amount' => $payNow,
            'type'   => $paymentStatus === 'paid' ? 'full' : 'dp',
        ]);

        // SINKRON BOOKING
        if ($paymentStatus === 'paid') {
            $payment->booking->update([
                'status' => 'confirmed',
            ]);
        } elseif ($paymentStatus === 'dp') {
            $payment->booking->update([
                'status' => 'waiting_payment',
            ]);
        }

        return redirect()
            ->route('payments.show', $payment)
            ->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function invoice(Payment $payment)
{
    $payment->load([
        'booking.customer',
        'booking.service',
        'histories'
    ]);

    return view('payments.invoice', compact('payment'));
}


public function financeReport(Request $request)
{
    $month = $request->month ?? now()->format('Y-m');

    $start = Carbon::parse($month)->startOfMonth();
    $end   = Carbon::parse($month)->endOfMonth();

    $payments = Payment::with('booking.service')
        ->where('status', 'paid')
        ->whereBetween('created_at', [$start, $end])
        ->orderBy('created_at')
        ->get();

    $totalRevenue = $payments->sum('paid_amount');

    return view('reports.finance', compact(
        'payments',
        'totalRevenue',
        'month'
    ));
}



}
