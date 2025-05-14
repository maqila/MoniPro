<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Timeline;
use App\Models\Customer;
use App\Models\Collaboration;
use Illuminate\Support\Facades\DB;

class Dashboard extends Controller
{
    //
    public function index()
    {

        // $customerCount = Customer::count();
        // $timelineCount = Timeline::count();

        // Top 3 timelines with shortest deadlines
        $topTimelines = Timeline::all()->filter(function ($timeline) {
            return $timeline->deadline !== 'Done';
        })->sortBy('days_remaining')->take(3);

        // Top 3 customers with 'Good' status and latest collaborations
        $topCustomers = Customer::all()->filter(function ($customer) {
            return in_array($customer->status, ['Baik Sekali', 'Baik']);
        })->sortByDesc('last_kerjasama')->take(3);

        // Count of customers with 'Good' status
        $customerCount = Customer::all()->filter(function ($customer) {
            return in_array($customer->status, ['Baik Sekali', 'Baik']);
        })->sortByDesc('last_kerjasama')->count();

        // Count of timelines with 'Good' status
        $timelineCount = Timeline::all()->filter(function ($timeline) {
            return $timeline->deadline !== 'Done';
        })->sortBy('days_remaining')->count();

        // Fetch monthly collaboration data (replace `tanggal` with your date column)
        $collaborations = Collaboration::selectRaw("strftime('%m-%Y', tanggal) as month_year, COUNT(*) as count")
            ->groupBy('month_year')
            ->orderByRaw("MIN(tanggal) ASC")
            ->get();

        // Get count of each customer type
        $customerTypes = Customer::select('jenis_customer', DB::raw('COUNT(*) as count'))
            ->groupBy('jenis_customer')
            ->pluck('count', 'jenis_customer');

        return view('dashboard.index', compact('customerCount', 'timelineCount', 'topTimelines', 'topCustomers', 'collaborations', 'customerTypes'));
    }
}
