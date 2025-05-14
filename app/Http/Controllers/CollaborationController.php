<?php

namespace App\Http\Controllers;

use App\Models\Collaboration;
use App\Models\Customer;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CollaborationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $month = $request->month;
        $year = $request->year;
        $searchCustomer = $request->customer;
        $searchKode = $request->kode;

        $collaborations = Collaboration::with('customer')
            ->when($month, fn($q) => $q->whereMonth('tanggal', $month))
            ->when($year, fn($q) => $q->whereYear('tanggal', $year))
            ->when($searchCustomer, fn($q) => $q->whereHas('customer', fn($c) => $c->where('nama', 'like', '%' . $searchCustomer . '%')))
            ->when($searchKode, fn($q) => $q->where('kode', 'like', '%' . $searchKode . '%'))
            ->orderBy('tanggal', 'desc')
            ->get();

        $customers = Customer::all(); // For dropdown in the modal

        return view('dashboard.collaboration.index', compact('collaborations', 'customers'));
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
            'kode' => 'required|string|max:10',
            'customer_id' => 'required|exists:customers,id',
            'nama_proyek' => 'required|string|max:100',
            'pic' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'kepatuhan_pembayaran' => 'required|integer|min:1|max:4',
            'komitmen_kontrak' => 'required|integer|min:1|max:4',
            'respon_komunikasi' => 'required|integer|min:1|max:4',
            'pengambilan_keputusan' => 'required|integer|min:1|max:4',
            'dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'pic_se' => 'required|string|max:50',
        ]);

        $data = $request->all();

        // Calculate the collaboration status based on average score
        $averageScore = ($request->kepatuhan_pembayaran * 0.5) + ($request->komitmen_kontrak * 0.2) + ($request->respon_komunikasi * 0.15) + ($request->pengambilan_keputusan * 0.15);


        // Determine the status based on average score
        if ($averageScore > 3) {
            $data['status'] = 'Baik Sekali';
        } elseif ($averageScore > 2) {
            $data['status'] = 'Baik';
        } elseif ($averageScore > 1) {
            $data['status'] = 'Jelek';
        } else {
            $data['status'] = 'Jelek Sekali';
        }

        // Handle file upload if exists
        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('dokumenCollabs'), $filename);
            $data['dokumen'] = $filename;
        }

        Collaboration::create($data);

        return redirect()->back()->with('success', 'Collaboration created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Collaboration $collaboration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Collaboration $collaboration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Collaboration $collaboration)
    {
        //
        $request->validate([
            'kode' => 'required|string|max:10',
            'customer_id' => 'required|exists:customers,id',
            'nama_proyek' => 'required|string|max:100',
            'pic' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'kepatuhan_pembayaran' => 'required|integer|min:1|max:4',
            'komitmen_kontrak' => 'required|integer|min:1|max:4',
            'respon_komunikasi' => 'required|integer|min:1|max:4',
            'pengambilan_keputusan' => 'required|integer|min:1|max:4',
            'dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'pic_se' => 'required|string|max:50',
        ]);

        $data = $request->all();

        // Calculate the collaboration status based on average score
        $averageScore = ($request->kepatuhan_pembayaran * 0.5) + ($request->komitmen_kontrak * 0.2) + ($request->respon_komunikasi * 0.15) + ($request->pengambilan_keputusan * 0.15);

        // Determine the status based on average score
        if ($averageScore > 3) {
            $data['status'] = 'Baik Sekali';
        } elseif ($averageScore > 2) {
            $data['status'] = 'Baik';
        } elseif ($averageScore > 1) {
            $data['status'] = 'Jelek';
        } else {
            $data['status'] = 'Jelek Sekali';
        }

        // Handle file upload if exists and remove old file
        if ($request->hasFile('dokumen')) {
            if ($collaboration->dokumen) {
                $oldFilePath = public_path('dokumenCollabs/' . $collaboration->dokumen);
                if (file_exists($oldFilePath)) {
                    try {
                        unlink($oldFilePath);
                    } catch (\Exception $e) {
                        return redirect()->back()->with('error', 'Error deleting old file: ' . $e->getMessage());
                    }
                }
            }

            $file = $request->file('dokumen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('dokumenCollabs'), $filename);
            $data['dokumen'] = $filename;
        }

        $collaboration->update($data);
        $collaboration->save();

        return redirect()->back()->with('success', 'Collaboration updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collaboration $collaboration)
    {
        //
        if ($collaboration->dokumen) {
            $oldFilePath = public_path('dokumenCollabs/' . $collaboration->dokumen);
            if (file_exists($oldFilePath)) {
                try {
                    unlink($oldFilePath);
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'Error deleting file: ' . $e->getMessage());
                }
            }
        }

        $collaboration->delete();

        return redirect()->back()->with('success', 'Collaboration deleted successfully.');
    }

    public function exportPdf(Request $request)
    {
        $month = $request->month;
        $year = $request->year;

        $collaborations = Collaboration::with('customer')
            ->when($month, fn($q) => $q->whereMonth('tanggal', $month))
            ->when($year, fn($q) => $q->whereYear('tanggal', $year))
            ->orderBy('tanggal', 'desc')
            ->get();

        return Pdf::loadView('dashboard.collaboration.export', [
            'collaborations' => $collaborations,
            'month' => $month,
            'year' => $year,
        ])->setPaper('A4', 'landscape')->download('Daftar_Kolaborasi.pdf');
    }
}
