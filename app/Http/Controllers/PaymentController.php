<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /* =========================
     * LIST PEMBAYARAN
     * ========================= */
    public function index()
    {
        $payments = Payment::with(['booking.customer', 'booking.service'])
            ->latest()
            ->get();

        return view('payments.index', compact('payments'));
    }

    /* =========================
     * DETAIL PEMBAYARAN
     * ========================= */
    public function show(Payment $payment)
    {
        // 🔥 AUTO FIX STATUS (DP → PAID kalau sisa 0)
        if (
            $payment->remaining_amount == 0 &&
            $payment->status !== 'paid'
        ) {
            $payment->update(['status' => 'paid']);
        }

        // 🔥 AUTO FIX BOOKING STATUS
        if (
            $payment->status === 'paid' &&
            $payment->booking->status !== 'confirmed'
        ) {
            $payment->booking->update([
                'status' => 'confirmed'
            ]);
        }

        $payment->load([
            'booking.customer',
            'booking.service',
            'histories'
        ]);

        return view('payments.show', compact('payment'));
    }



    /* =========================
     * PROSES PEMBAYARAN (DP / LUNAS)
     * ========================= */
    public function update(Request $request, Payment $payment)
{
    $request->validate([
        'pay_now' => 'required|numeric|min:1',
    ]);

    $payNow = (int) $request->pay_now;

    if ($payNow > $payment->remaining_amount) {
        return back()->withErrors([
            'pay_now' => 'Nominal melebihi sisa pembayaran.'
        ]);
    }

    $payment->update([
        'paid_amount'      => $payment->paid_amount + $payNow,
        'remaining_amount' => $payment->remaining_amount - $payNow,
    ]);

    $payment->histories()->create([
        'amount' => $payNow,
        'type'   => $payment->remaining_amount - $payNow == 0 ? 'full' : 'dp',
    ]);

    return back()->with('success', 'Pembayaran berhasil dicatat.');
}



    /* =========================
     * CETAK INVOICE
     * ========================= */
    public function invoice(Payment $payment)
    {
        $payment->load([
            'booking.customer',
            'booking.service',
            'histories'
        ]);

        return view('payments.invoice', compact('payment'));
    }

    /* =========================
     * LAPORAN KEUANGAN
     * ========================= */
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
