<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // KPI
        $totalBooking = Booking::count();
        $totalCustomer = Customer::count();

        // Revenue = uang yang sudah dibayar
        $totalRevenue = Payment::where('status', 'paid')
            ->sum('paid_amount');

        $pendingBooking = Booking::where('status', 'pending')->count();

        // Booking terbaru
        $latestBookings = Booking::with(['customer', 'service'])
            ->latest()
            ->take(5)
            ->get();

        // Booking trends (optional – nanti buat chart)
        $bookingTrends = Booking::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('dashboard', compact(
            'totalBooking',
            'totalCustomer',
            'totalRevenue',
            'pendingBooking',
            'latestBookings',
            'bookingTrends'
        ));
    }
}
