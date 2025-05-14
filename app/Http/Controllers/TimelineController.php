<?php

namespace App\Http\Controllers;

use App\Models\Timeline;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class TimelineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Timeline::query();

        if ($request->filled('month')) {
            $query->whereMonth('tanggal', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('tanggal', $request->year);
        }

        $timelines = $query->orderBy('tanggal')->get()->sortBy('days_remaining');
        $timelineDone = Timeline::where('deadline', 'Done')->get()->sortBy('days_remaining');

        return view('dashboard.timeline.index', compact('timelines', 'timelineDone'));
    }

    public function print(Request $request)
    {
        $query = Timeline::query();

        if ($request->filled('month')) {
            $query->whereMonth('tanggal', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('tanggal', $request->year);
        }

        $timelines = $query->orderBy('tanggal')->get();

        return view('dashboard.timeline.print', compact('timelines'));
    }

    public function exportPdf(Request $request)
    {
        $query = Timeline::query();

        if ($request->filled('month')) {
            $query->whereMonth('tanggal', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('tanggal', $request->year);
        }

        $timelines = $query->orderBy('tanggal')->get();

        $pdf = Pdf::loadView('dashboard.timeline.export-pdf', compact('timelines'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('timeline_report.pdf');
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
            'tanggal' => 'required|date',
            'pic_se' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
        ]);

        $deadline = Carbon::parse($request->tanggal)->addDays(7);

        Timeline::create($request->all() + ['deadline' => $deadline]);

        return redirect()->back()->with('success', 'Timeline created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Timeline $timeline)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Timeline $timeline)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Timeline $timeline)
    {
        //
        // $request->validate([
        //     'tanggal' => 'required|date',
        //     'pic_se' => 'required|string|max:255',
        //     'nama' => 'required|string|max:255',
        //     'contact' => 'required|string|max:255',
        // ]);

        // $deadline = Carbon::parse($request->tanggal)->addDays(7);

        // $timeline->update($request->all() + ['deadline' => $deadline]);

        // return redirect()->back()->with('success', 'Timeline updated successfully.');\
        $timeline = Timeline::findOrFail($timeline->id);
        $timeline->update($request->all());

        return redirect()->back()->with('success', 'Timeline updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Timeline $timeline)
    {
        //
        $timeline->delete();
        return redirect()->back()->with('success', 'Timeline deleted successfully.');
    }

    public function markAsDone($id)
    {
        $timeline = Timeline::findOrFail($id);

        // Update the deadline field to show 'Done'
        $timeline->deadline = 'Done';
        $timeline->save();

        return response()->json(['success' => true]);
    }
}
