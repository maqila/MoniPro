<x-dashboard>
    <div class="row" id="table-hover-row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Timeline</h4>
                    @role(1, 3)
                        <button class="btn btn-primary rounded-pill" data-bs-toggle="modal"
                            data-bs-target="#createTimelineModal">Add
                            Timeline</button>
                    @endrole
                </div>
                <div class="card-content">
                    <div class="px-3 pb-3">
                        <form method="GET" action="{{ route('timeline.index') }}" class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label for="filter_month" class="form-label">Filter Bulan</label>
                                <select class="form-select" id="filter_month" name="month">
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
                                <label for="filter_year" class="form-label">Filter Tahun</label>
                                <select class="form-select" id="filter_year" name="year">
                                    <option value="">-- Semua Tahun --</option>
                                    @foreach (range(now()->year, 2022) as $y)
                                        <option value="{{ $y }}"
                                            {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="search_name" class="form-label">Cari Nama</label>
                                <input type="text" class="form-control" id="search_name" name="search_name"
                                    value="{{ request('search_name') }}" placeholder="Masukkan nama...">
                            </div>
                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-outline-primary rounded-pill">Filter</button>

                                @role(2, 3)
                                    <a href="{{ route('timeline.print', ['month' => request('month'), 'year' => request('year')]) }}"
                                        target="_blank" class="btn btn-outline-success rounded-pill">Cetak</a>

                                    <a href="{{ route('timeline.exportPdf', ['month' => request('month'), 'year' => request('year')]) }}"
                                        target="_blank" class="btn btn-outline-danger rounded-pill">Export PDF</a>
                                @endrole
                            </div>
                        </form>
                    </div>

                    <!-- table hover -->
                    <div class="table-responsive">
                        <table class="table table-hover mb-4">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Tempat</th>
                                    <th>PIC/SE</th>
                                    <th>Keterangan</th>
                                    <th>Perusahaan</th>
                                    <th>Nama</th>
                                    <th>Contact</th>
                                    <th>Deadline</th>
                                    @role(1, 3)
                                        <th>ACTION</th>
                                    @endrole
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($timelines as $timeline)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($timeline->tanggal)->translatedFormat('d F Y') }}
                                        </td>
                                        <td>{{ $timeline->jam }}</td>
                                        <td>{{ $timeline->tempat }}</td>
                                        <td>{{ $timeline->pic_se }}</td>
                                        <td>{{ $timeline->keterangan }}</td>
                                        <td>{{ $timeline->perusahaan }}</td>
                                        <td>{{ $timeline->nama }}</td>
                                        <td>{{ $timeline->contact }}</td>
                                        <td>
                                            @if ($timeline->deadline === 'Done')
                                                Done
                                            @elseif ($timeline->days_remaining > 0)
                                                {{ $timeline->days_remaining }} days remaining
                                            @elseif ($timeline->days_remaining == 0)
                                                Due today
                                            @else
                                                {{ abs($timeline->days_remaining) }} days overdue
                                            @endif
                                        </td>
                                        @role(1, 3)
                                            <td>
                                                <!-- Edit Icon -->
                                                <a href="javascript:void(0);"
                                                    onclick="openEditTimelineModal({{ $timeline }})" title="Edit">
                                                    <i data-feather="edit" class="text-primary"></i>
                                                </a>

                                                <!-- Delete Icon -->
                                                <form action="{{ route('timeline.destroy', $timeline) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Are you sure?')"
                                                        title="Delete"
                                                        style="border:none; background:none; cursor:pointer;">
                                                        <i data-feather="trash-2" class="text-danger"></i>
                                                    </button>
                                                </form>

                                                <!-- Selesai Icon -->
                                                <a href="javascript:void(0);" onclick="markAsDone({{ $timeline->id }})"
                                                    title="Selesai">
                                                    <i data-feather="check-circle" class="text-success"></i>
                                                </a>
                                            </td>
                                        @endrole
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- table hover -->
                    <div class="table-responsive">
                        <table class="table table-hover mb-4">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Tempat</th>
                                    <th>PIC/SE</th>
                                    <th>Keterangan</th>
                                    <th>Perusahaan</th>
                                    <th>Nama</th>
                                    <th>Contact</th>
                                    <th>Deadline</th>
                                    @role(1, 3)
                                        <th>ACTION</th>
                                    @endrole
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($timelineDone as $timeline)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($timeline->tanggal)->translatedFormat('d F Y') }}
                                        </td>
                                        <td>{{ $timeline->jam }}</td>
                                        <td>{{ $timeline->tempat }}</td>
                                        <td>{{ $timeline->pic_se }}</td>
                                        <td>{{ $timeline->keterangan }}</td>
                                        <td>{{ $timeline->perusahaan }}</td>
                                        <td>{{ $timeline->nama }}</td>
                                        <td>{{ $timeline->contact }}</td>
                                        <td>
                                            @if ($timeline->deadline === 'Done')
                                                Done
                                            @endif
                                        </td>
                                        @role(1, 3)
                                            <td>
                                                <!-- Edit Icon -->
                                                <a href="javascript:void(0);"
                                                    onclick="openEditTimelineModal({{ $timeline }})" title="Edit">
                                                    <i data-feather="edit" class="text-primary"></i>
                                                </a>

                                                <!-- Delete Icon -->
                                                <form action="{{ route('timeline.destroy', $timeline) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Are you sure?')"
                                                        title="Delete"
                                                        style="border:none; background:none; cursor:pointer;">
                                                        <i data-feather="trash-2" class="text-danger"></i>
                                                    </button>
                                                </form>

                                                <!-- Selesai Icon -->
                                                <a href="javascript:void(0);" onclick="markAsDone({{ $timeline->id }})"
                                                    title="Selesai">
                                                    <i data-feather="check-circle" class="text-success"></i>
                                                </a>
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

    <!-- Create Timeline Modal -->
    <div class="modal fade" id="createTimelineModal" tabindex="-1" aria-labelledby="createTimelineModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('timeline.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createTimelineModalLabel">Tambah Data Timeline</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal" class="form-label">Tanggal</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="jam" class="form-label">Jam (Opsional)</label>
                                    <input type="time" class="form-control" id="jam" name="jam">
                                </div>
                                <div class="mb-3">
                                    <label for="tempat" class="form-label">Tempat (Opsional)</label>
                                    <input type="text" class="form-control" id="tempat" name="tempat">
                                </div>
                                <div class="mb-3">
                                    <label for="pic_se" class="form-label">PIC/SE</label>
                                    <input type="text" class="form-control" id="pic_se" name="pic_se"
                                        required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="2"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="perusahaan" class="form-label">Perusahaan (Opsional)</label>
                                    <input type="text" class="form-control" id="perusahaan" name="perusahaan">
                                </div>
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="nama" name="nama"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="contact" class="form-label">Contact</label>
                                    <input type="text" class="form-control" id="contact" name="contact"
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Timeline Modal -->
    <div class="modal fade" id="editTimelineModal" tabindex="-1" aria-labelledby="editTimelineModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editTimelineForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTimelineModalLabel">Edit Data Timeline</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal" class="form-label">Tanggal</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="jam" class="form-label">Jam (Opsional)</label>
                                    <input type="time" class="form-control" id="jam" name="jam">
                                </div>
                                <div class="mb-3">
                                    <label for="tempat" class="form-label">Tempat (Opsional)</label>
                                    <input type="text" class="form-control" id="tempat" name="tempat">
                                </div>
                                <div class="mb-3">
                                    <label for="pic_se" class="form-label">PIC/SE</label>
                                    <input type="text" class="form-control" id="pic_se" name="pic_se"
                                        required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="2"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="perusahaan" class="form-label">Perusahaan (Opsional)</label>
                                    <input type="text" class="form-control" id="perusahaan" name="perusahaan">
                                </div>
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="nama" name="nama"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="contact" class="form-label">Contact</label>
                                    <input type="text" class="form-control" id="contact" name="contact"
                                        required>
                                </div>
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
        function openEditTimelineModal(timeline) {
            // Set the form action URL dynamically
            const form = document.getElementById('editTimelineForm');
            form.action = `/timeline/${timeline.id}`;

            // Populate the form fields with the timeline data
            document.getElementById('editTimelineModal').querySelector('input[name="tanggal"]').value = timeline.tanggal;
            document.getElementById('editTimelineModal').querySelector('input[name="jam"]').value = timeline.jam || '';
            document.getElementById('editTimelineModal').querySelector('input[name="tempat"]').value = timeline.tempat ||
                '';
            document.getElementById('editTimelineModal').querySelector('input[name="pic_se"]').value = timeline.pic_se;
            document.getElementById('editTimelineModal').querySelector('textarea[name="keterangan"]').value = timeline
                .keterangan || '';
            document.getElementById('editTimelineModal').querySelector('input[name="perusahaan"]').value = timeline
                .perusahaan || '';
            document.getElementById('editTimelineModal').querySelector('input[name="nama"]').value = timeline.nama;
            document.getElementById('editTimelineModal').querySelector('input[name="contact"]').value = timeline.contact;

            // Open the modal
            new bootstrap.Modal(document.getElementById('editTimelineModal')).show();
        }

        function markAsDone(id) {
            fetch(`/timelines/${id}/done`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Laravel CSRF token for security
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Timeline marked as Done.');
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert('Failed to mark as Done.');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>

</x-dashboard>
