<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collaboration;

class CustomerCollaborationController extends Controller
{
    //
    public function update(Request $request, Collaboration $collaboration)
    {
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

        if ($request->hasFile('dokumen')) {
            if ($collaboration->dokumen) {
                $oldFilePath = public_path('dokumenCollabs/' . $collaboration->dokumen);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            $file = $request->file('dokumen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('dokumenCollabs'), $filename);
            $data['dokumen'] = $filename;
        }

        $collaboration->update($data);

        return redirect()->route('customer.details', $collaboration->customer_id)
            ->with('success', 'Collaboration updated successfully.');
    }
}
