<x-dashboard>
    <div class="row" id="table-hover-row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Detail Customer: {{ $customer->nama }}</h4>
                    <div>
                        <a href="{{ route('customer.index') }}"
                            class="btn btn-outline-secondary btn-sm rounded-pill">Back</a>
                        <button class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal"
                            data-bs-target="#createCollaborationModal">
                            Add Collaboration
                        </button>
                        @role(2, 3)
                            <a href="{{ route('customer.print', $customer->id) }}"
                                class="btn btn-danger btn-sm rounded-pill">Cetak PDF</a>
                        @endrole
                    </div>
                </div>

                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Email:</strong> {{ $customer->email }}</p>
                                <p><strong>Contact:</strong> {{ $customer->contact }}</p>
                                <p><strong>Jenis Customer:</strong> {{ ucfirst($customer->jenis_customer) }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Last Kerjasama:</strong>
                                    {{ $customer->last_kerjasama ? \Carbon\Carbon::parse($customer->last_kerjasama)->translatedFormat('d F Y') : 'No data' }}
                                </p>
                                <p><strong>Status:</strong> {{ ucfirst($customer->status) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3 px-3">
                        <form method="GET" action="{{ route('customer.details', $customer->id) }}"
                            class="row g-2 mb-3">
                            <div class="col-md-3">
                                <select name="month" class="form-select">
                                    <option value="">-- Semua Bulan --</option>
                                    @foreach (range(1, 12) as $m)
                                        <option value="{{ $m }}"
                                            {{ request('month') == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="year" class="form-select">
                                    <option value="">-- Semua Tahun --</option>
                                    @foreach (range(now()->year, 2020) as $y)
                                        <option value="{{ $y }}"
                                            {{ request('year') == $y ? 'selected' : '' }}>
                                            {{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 d-flex gap-2">
                                <button class="btn btn-outline-primary" type="submit">Filter</button>
                                @role(2, 3)
                                    <a href="{{ route('customer.printFiltered', ['id' => $customer->id, 'month' => request('month'), 'year' => request('year')]) }}"
                                        class="btn btn-outline-danger" target="_blank">Export PDF</a>
                                @endrole
                            </div>
                        </form>
                    </div>

                    <!-- Table for displaying collaborations -->
                    <div class="table-responsive px-3">
                        <h5 class="mb-3">Collaborations</h5>
                        <table class="table table-hover mb-4" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Proyek</th>
                                    <th>PIC</th>
                                    <th>Jabatan</th>
                                    <th>Contact</th>
                                    <th>Tanggal</th>
                                    <th>Kepatuhan Pembayaran</th>
                                    <th>Komitmen Kontrak</th>
                                    <th>Respon Komunikasi</th>
                                    <th>Pengambilan Keputusan</th>
                                    <th>Status</th>
                                    <th>Dokumen</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($collaborations as $collaboration)
                                    <tr>
                                        <td>{{ $collaboration->kode }}</td>
                                        <td>{{ $collaboration->nama_proyek }}</td>
                                        <td>{{ $collaboration->pic }}</td>
                                        <td>{{ $collaboration->jabatan }}</td>
                                        <td>{{ $collaboration->contact }}</td>
                                        <td>{{ \Carbon\Carbon::parse($collaboration->tanggal)->translatedFormat('d F Y') }}
                                        </td>
                                        <td>{{ $collaboration->kepatuhan_pembayaran_text }}</td>
                                        <td>{{ $collaboration->komitmen_kontrak_text }}</td>
                                        <td>{{ $collaboration->respon_komunikasi_text }}</td>
                                        <td>{{ $collaboration->pengambilan_keputusan_text }}</td>
                                        <td>{{ $collaboration->status }}</td>
                                        <td>
                                            @if ($collaboration->dokumen)
                                                <a href="{{ asset('dokumenCollabs/' . $collaboration->dokumen) }}"
                                                    download>{{ $collaboration->dokumen }}</a>
                                            @else
                                                No Document
                                            @endif
                                        </td>
                                        <td>
                                            <!-- Delete Icon -->
                                            <form action="{{ route('collaboration.destroy', $collaboration) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Are you sure?')"
                                                    title="Delete"
                                                    style="border:none; background:none; cursor:pointer;">
                                                    <i data-feather="trash-2" class="text-danger"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Collaboration Modal -->
    <div class="modal fade" id="createCollaborationModal" tabindex="-1" aria-labelledby="createCollaborationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('collaboration.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Hidden customer_id to link collaboration to the current customer -->
                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">

                    <div class="modal-header">
                        <h5 class="modal-title" id="createCollaborationModalLabel">Add Collaboration</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="kode" class="form-label">Kode</label>
                                <input type="text" name="kode" class="form-control" id="kode" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nama_proyek" class="form-label">Nama Proyek</label>
                                <input type="text" name="nama_proyek" class="form-control" id="nama_proyek" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pic" class="form-label">PIC</label>
                                <input type="text" name="pic" class="form-control" id="pic" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jabatan" class="form-label">Jabatan</label>
                                <input type="text" name="jabatan" class="form-control" id="jabatan">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contact" class="form-label">Contact</label>
                                <input type="text" name="contact" class="form-control" id="contact">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" id="tanggal" required>
                            </div>

                            <!-- Riwayat Komunikasi Section -->
                            <div class="col-md-6 mb-3">
                                <label for="kepatuhan_pembayaran" class="form-label">Kepatuhan Pembayaran</label>
                                <select name="kepatuhan_pembayaran" id="kepatuhan_pembayaran" class="form-control"
                                    required>
                                    <option value="4">Terlambat < 14 Hari / Tepat Waktu</option>
                                    <option value="3">Terlambat 15 s/d 30 Hari</option>
                                    <option value="2">Terlambat 30 s/d 60 Hari</option>
                                    <option value="1">Terlambat > 60 Hari</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="komitmen_kontrak" class="form-label">Komitmen Pada Kontrak</label>
                                <select name="komitmen_kontrak" id="komitmen_kontrak" class="form-control" required>
                                    <option value="4">Taat Pada Kontrak</option>
                                    <option value="3">Beberapa Perubahan Kecil</option>
                                    <option value="2">Beberapa Perubahan Besar</option>
                                    <option value="1">Perubahan Mendadak / Tidak Mengikuti Kontrak</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="respon_komunikasi" class="form-label">Respon Komunikasi</label>
                                <select name="respon_komunikasi" id="respon_komunikasi" class="form-control"
                                    required>
                                    <option value="4">Cepat</option>
                                    <option value="3">Cukup Cepat</option>
                                    <option value="2">Lambat</option>
                                    <option value="1">Lambat Sekali</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pengambilan_keputusan" class="form-label">Pengambilan Keputusan</label>
                                <select name="pengambilan_keputusan" id="pengambilan_keputusan" class="form-control"
                                    required>
                                    <option value="4">Cepat dan Tepat</option>
                                    <option value="3">Cukup Cepat</option>
                                    <option value="2">Lambat</option>
                                    <option value="1">Sangat Lambat</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="dokumen" class="form-label">Dokumen</label>
                                <input type="file" name="dokumen" class="form-control" id="dokumen">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pic_se" class="form-label">PIC SE</label>
                                <input type="text" name="pic_se" class="form-control" id="pic_se">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-dashboard>
