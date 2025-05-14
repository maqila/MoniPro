<?php

namespace App\Http\Controllers;

use App\Models\Customer;
// use Barryvdh\DomPDF\PDF;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $customers = Customer::with('collaborations')
            ->when($request->search_name, function ($query, $searchName) {
                $query->where('nama', 'like', '%' . $searchName . '%');
            })
            ->when($request->search_jenis, function ($query, $searchJenis) {
                $query->where('jenis_customer', $searchJenis);
            })
            ->get();
        return view('dashboard.customer.index', compact('customers'));
    }

    public function details(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $collaborations = $customer->collaborations()
            ->when($request->month, fn($q) => $q->whereMonth('tanggal', $request->month))
            ->when($request->year, fn($q) => $q->whereYear('tanggal', $request->year))
            ->orderByDesc('tanggal')
            ->get();

        return view('dashboard.customer.details', compact('customer', 'collaborations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'nama' => 'required|string',
            'jenis_customer' => 'required|string',
            'email' => 'nullable|email',
            'contact' => 'nullable|string',
            'aniversary' => 'nullable|date',
            'media_sosial' => 'nullable|string',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string'
        ]);

        Customer::create($request->all());

        return redirect()->back()->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        //
        $customer = Customer::findOrFail($customer->id);
        $customer->update($request->all());

        return redirect()->back()->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
        $customer->delete();

        return response()->json(['success' => 'Customer deleted successfully.']);
    }

    public function printPDF($id, PDF $pdf) // Dependency injection di parameter
    {
        $customer = Customer::findOrFail($id);
        $collaborations = $customer->collaborations;

        $pdf = Pdf::loadView('dashboard.customer.pdf', compact('customer', 'collaborations'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('customer_detail_' . $customer->nama . '.pdf');
    }

    public function printFiltered(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $collaborations = $customer->collaborations()
            ->when($request->month, fn($q) => $q->whereMonth('tanggal', $request->month))
            ->when($request->year, fn($q) => $q->whereYear('tanggal', $request->year))
            ->orderByDesc('tanggal')
            ->get();

        $pdf = Pdf::loadView('dashboard.customer.export-collabs-pdf', compact('customer', 'collaborations'))
            ->setPaper('A4', 'landscape');

        return $pdf->download("Collaborations_{$customer->nama}.pdf");
    }
}
