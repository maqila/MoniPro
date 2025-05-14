<x-dashboard>
    <div class="row" id="table-hover-row">
        <div class="col-12">
            <div class="card">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>There were some errors with your input:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Collaboration</h4>
                    <form method="GET" action="{{ route('collaboration.index') }}"
                        class="d-flex align-items-center gap-2 mb-3 px-3">
                        <select name="month" class="form-select form-select-sm w-auto">
                            <option value="">Semua Bulan</option>
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>

                        <select name="year" class="form-select form-select-sm w-auto">
                            <option value="">Semua Tahun</option>
                            @foreach (range(now()->year, now()->year - 10) as $y)
                                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                    {{ $y }}</option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn btn-outline-primary btn-sm">Terapkan</button>
                        @role(2, 3)
                            <a href="{{ route('collaboration.exportPdf', ['month' => request('month'), 'year' => request('year')]) }}"
                                class="btn btn-outline-danger btn-sm" target="_blank">Export PDF</a>
                        @endrole
                    </form>
                </div>
                <div class="card-content">
                    <!-- Table for displaying collaborations -->
                    <div class="table-responsive">
                        <table class="table table-hover mb-4" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Kode</th>
                                    <th>Nama Proyek</th>
                                    <th>PIC</th>
                                    <th>Jabatan</th>
                                    <th>Contact</th>
                                    <th>Tanggal</th>
                                    {{-- <th>Kepatuhan Pembayaran</th>
                                    <th>Komitmen Kontrak</th>
                                    <th>Respon Komunikasi</th>
                                    <th>Pengambilan Keputusan</th> --}}
                                    <th>Status</th>
                                    <th>Dokumen</th>
                                    <th>PIC SE</th>
                                    @role(1, 3)
                                        <th>ACTION</th>
                                    @endrole
                                </tr>
                            </thead>
                            <tbody id="collaborationsTable">
                                @foreach ($collaborations as $collaboration)
                                    <tr data-month="{{ \Carbon\Carbon::parse($collaboration->tanggal)->month }}"
                                        data-year="{{ \Carbon\Carbon::parse($collaboration->tanggal)->year }}">
                                        <td>{{ $collaboration->customer->nama }}</td>
                                        <td>{{ $collaboration->kode }}</td>
                                        <td>{{ $collaboration->nama_proyek }}</td>
                                        <td>{{ $collaboration->pic }}</td>
                                        <td>{{ $collaboration->jabatan }}</td>
                                        <td>{{ $collaboration->contact }}</td>
                                        <td>{{ \Carbon\Carbon::parse($collaboration->tanggal)->translatedFormat('d F Y') }}
                                        </td>
                                        {{-- <td>{{ $collaboration->kepatuhan_pembayaran_text }}</td>
                                        <td>{{ $collaboration->komitmen_kontrak_text }}</td>
                                        <td>{{ $collaboration->respon_komunikasi_text }}</td>
                                        <td>{{ $collaboration->pengambilan_keputusan_text }}</td> --}}
                                        <td>{{ $collaboration->status }}</td>
                                        <td>
                                            @if ($collaboration->dokumen)
                                                <a href="{{ asset('dokumenCollabs/' . $collaboration->dokumen) }}"
                                                    download>{{ $collaboration->dokumen }}</a>
                                            @else
                                                No Document
                                            @endif
                                        </td>
                                        <td>{{ $collaboration->pic_se }}</td>
                                        @role(1, 3)
                                            <td>
                                                <!-- Edit Icon -->
                                                <a href="javascript:void(0);"
                                                    onclick="openEditCollaborationModal({{ $collaboration }})"
                                                    title="Edit">
                                                    <i data-feather="edit" class="text-primary"></i>
                                                </a>
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
                                        @endrole
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
                                <label for="customer_id" class="form-label">Customer</label>
                                <select name="customer_id" class="form-control" id="customer_id" required>
                                    <option value="">Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->nama }}</option>
                                    @endforeach
                                </select>
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

    <!-- Edit Collaboration Modal -->
    <div class="modal fade" id="editCollaborationModal" tabindex="-1" aria-labelledby="editCollaborationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editCollaborationForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCollaborationModalLabel">Edit Collaboration</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Hidden field for ID -->
                            <input type="hidden" name="id" id="edit-id">

                            <!-- Kode and Customer -->
                            <div class="col-md-6 mb-3">
                                <label for="edit-kode" class="form-label">Kode</label>
                                <input type="text" name="kode" class="form-control" id="edit-kode" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit-customer_id" class="form-label">Customer</label>
                                <select name="customer_id" class="form-control" id="edit-customer_id" required>
                                    <option value="">Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Nama Proyek and PIC -->
                            <div class="col-md-6 mb-3">
                                <label for="edit-nama_proyek" class="form-label">Nama Proyek</label>
                                <input type="text" name="nama_proyek" class="form-control" id="edit-nama_proyek"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit-pic" class="form-label">PIC</label>
                                <input type="text" name="pic" class="form-control" id="edit-pic" required>
                            </div>

                            <!-- Jabatan and Contact -->
                            <div class="col-md-6 mb-3">
                                <label for="edit-jabatan" class="form-label">Jabatan</label>
                                <input type="text" name="jabatan" class="form-control" id="edit-jabatan">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit-contact" class="form-label">Contact</label>
                                <input type="text" name="contact" class="form-control" id="edit-contact">
                            </div>

                            <!-- Tanggal and Kepatuhan Pembayaran -->
                            <div class="col-md-6 mb-3">
                                <label for="edit-tanggal" class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" id="edit-tanggal"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="kepatuhan_pembayaran" class="form-label">Kepatuhan Pembayaran</label>
                                <select name="kepatuhan_pembayaran" id="edit-kepatuhan_pembayaran"
                                    class="form-control" required>
                                    <option value="4">Terlambat < 14 Hari / Tepat Waktu</option>
                                    <option value="3">Terlambat 15 s/d 30 Hari</option>
                                    <option value="2">Terlambat 30 s/d 60 Hari</option>
                                    <option value="1">Terlambat > 60 Hari</option>
                                </select>
                            </div>

                            <!-- Komitmen Pada Kontrak and Respon Komunikasi -->
                            <div class="col-md-6 mb-3">
                                <label for="edit-komitmen_kontrak" class="form-label">Komitmen Pada Kontrak</label>
                                <select name="komitmen_kontrak" id="edit-komitmen_kontrak" class="form-control"
                                    required>
                                    <option value="4">Taat Pada Kontrak</option>
                                    <option value="3">Beberapa Perubahan Kecil</option>
                                    <option value="2">Beberapa Perubahan Besar</option>
                                    <option value="1">Perubahan Mendadak / Tidak Mengikuti Kontrak</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="respon_komunikasi" class="form-label">Respon Komunikasi</label>
                                <select name="respon_komunikasi" id="edit-respon_komunikasi" class="form-control"
                                    required>
                                    <option value="4">Cepat</option>
                                    <option value="3">Cukup Cepat</option>
                                    <option value="2">Lambat</option>
                                    <option value="1">Lambat Sekali</option>
                                </select>
                            </div>

                            <!-- Pengambilan Keputusan -->
                            <div class="col-md-6 mb-3">
                                <label for="edit-pengambilan_keputusan" class="form-label">Pengambilan
                                    Keputusan</label>
                                <select name="pengambilan_keputusan" id="edit-pengambilan_keputusan"
                                    class="form-control" required>
                                    <option value="4">Cepat dan Tepat</option>
                                    <option value="3">Cukup Cepat</option>
                                    <option value="2">Lambat</option>
                                    <option value="1">Sangat Lambat</option>
                                </select>
                            </div>

                            <!-- PIC SE -->
                            <div class="col-md-6 mb-3">
                                <label for="edit-pic_se" class="form-label">PIC SE</label>
                                <input type="text" name="pic_se" class="form-control" id="edit-pic_se">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="edit-dokumen" class="form-label">Dokumen</label>
                                <p id="current-dokumen" class="text-muted"></p>
                                <input type="file" name="dokumen" class="form-control" id="edit-dokumen">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        function openEditCollaborationModal(collaboration) {
            // Set the form action URL dynamically for the edit form
            const form = document.getElementById('editCollaborationForm');
            form.action = `/collaboration/${collaboration.id}`;

            // Populate the form fields with the collaboration's data
            document.getElementById('edit-id').value = collaboration.id;
            document.getElementById('edit-kode').value = collaboration.kode;
            document.getElementById('edit-customer_id').value = collaboration.customer_id;
            document.getElementById('edit-nama_proyek').value = collaboration.nama_proyek;
            document.getElementById('edit-pic').value = collaboration.pic;
            document.getElementById('edit-jabatan').value = collaboration.jabatan;
            document.getElementById('edit-contact').value = collaboration.contact;
            document.getElementById('edit-tanggal').value = collaboration.tanggal;

            // Set values for each select dropdown based on the data
            document.getElementById('edit-kepatuhan_pembayaran').value = collaboration.kepatuhan_pembayaran;
            document.getElementById('edit-komitmen_kontrak').value = collaboration.komitmen_kontrak;
            document.getElementById('edit-respon_komunikasi').value = collaboration.respon_komunikasi;
            document.getElementById('edit-pengambilan_keputusan').value = collaboration.pengambilan_keputusan;

            // Display current document name if it exists
            if (collaboration.dokumen) {
                document.getElementById('current-dokumen').textContent = `Current file: ${collaboration.dokumen}`;
            } else {
                document.getElementById('current-dokumen').textContent = 'No file uploaded';
            }

            document.getElementById('edit-pic_se').value = collaboration.pic_se;

            // Open the edit modal
            new bootstrap.Modal(document.getElementById('editCollaborationModal')).show();
        }

        // Function to filter collaborations by month and year
        function filterCollaborations() {
            const month = document.getElementById('monthFilter').value;
            const year = document.getElementById('yearFilter').value;
            const rows = document.querySelectorAll('#collaborationsTable tr');

            rows.forEach(row => {
                const rowMonth = row.getAttribute('data-month');
                const rowYear = row.getAttribute('data-year');

                if ((month === '' || rowMonth === month) && (year === '' || rowYear === year)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Function to clear filters
        function clearFilters() {
            document.getElementById('monthFilter').value = '';
            document.getElementById('yearFilter').value = '';
            filterCollaborations();
        }
    </script>


</x-dashboard>
