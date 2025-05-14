<x-dashboard>
    <div class="row" id="table-hover-row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Customer</h4>
                    <button class="btn btn-primary rounded-pill" data-bs-toggle="modal"
                        data-bs-target="#createCustomerModal">Add Customer</button>
                </div>
                <div class="card-content">
                    <!-- table hover -->
                    <div class="table-responsive">
                        <table class="table table-hover mb-4" style="font-size: 14px;">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Jenis Customer</th>
                                    <th>Email</th>
                                    <th>Contact</th>
                                    <th>Aniversary</th>
                                    <th>Media Sosial</th>
                                    <th>Alamat</th>
                                    <th>Kota</th>
                                    <th>Last Kerjasama</th>
                                    <th>Status</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $customer)
                                    <tr>
                                        <td>{{ $customer->nama }}</td>
                                        <td>{{ $customer->jenis_customer }}</td>
                                        <td>{{ $customer->email }}</td>
                                        <td>{{ $customer->contact }}</td>
                                        <td>{{ \Carbon\Carbon::parse($customer->aniversary)->translatedFormat('d F Y') }}
                                        </td>
                                        <td>{{ $customer->media_sosial }}</td>
                                        <td>{{ $customer->alamat }}</td>
                                        <td>{{ $customer->kota }}</td>
                                        <td>
                                            {{ $customer->last_kerjasama ? \Carbon\Carbon::parse($customer->last_kerjasama)->translatedFormat('d F Y') : 'No data' }}
                                        </td>
                                        <td>{{ $customer->status }}</td>
                                        <td>
                                            <!-- Detail Icon -->
                                            <a href="{{ route('customer.details', $customer->id) }}" title="Detail">
                                                <i data-feather="eye" class="text-info"></i>
                                            </a>
                                            <!-- Edit Icon -->
                                            <a href="javascript:void(0);"
                                                onclick="openEditCustomerModal({{ $customer }})" title="Edit">
                                                <i data-feather="edit" class="text-primary"></i>
                                            </a>
                                            <!-- Delete Icon -->
                                            <form action="{{ route('customer.destroy', $customer) }}" method="POST"
                                                style="display:inline;">
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

    <!-- Create Customer Modal -->
    <div class="modal fade" id="createCustomerModal" tabindex="-1" aria-labelledby="createCustomerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('customer.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createCustomerModalLabel">Add Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" name="nama" class="form-control" id="nama" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jenis_customer" class="form-label">Jenis Customer</label>
                                <select name="jenis_customer" class="form-control" id="jenis_customer" required>
                                    <option value="">Select Type</option>
                                    <option value="owner">Owner</option>
                                    <option value="contractor">Contractor</option>
                                    <option value="consultant">Consultant</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email (Opsional)</label>
                                <input type="email" name="email" class="form-control" id="email">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contact" class="form-label">Contact (Opsional)</label>
                                <input type="text" name="contact" class="form-control" id="contact">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="aniversary" class="form-label">Aniversary (Opsional)</label>
                                <input type="date" name="aniversary" class="form-control" id="aniversary">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="media_sosial" class="form-label">Media Sosial (Opsional)</label>
                                <input type="text" name="media_sosial" class="form-control" id="media_sosial">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="alamat" class="form-label">Alamat (Opsional)</label>
                                <input type="text" name="alamat" class="form-control" id="alamat">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="kota" class="form-label">Kota (Opsional)</label>
                                <input type="text" name="kota" class="form-control" id="kota">
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

    <!-- Edit Customer Modal -->
    <div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editCustomerForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCustomerModalLabel">Edit Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Same fields as the Create Modal with an additional hidden field for customer ID -->
                            <input type="hidden" name="id">
                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" name="nama" class="form-control" id="nama" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jenis_customer" class="form-label">Jenis Customer</label>
                                <select name="jenis_customer" class="form-control" id="jenis_customer" required>
                                    <option value="owner">Owner</option>
                                    <option value="contractor">Contractor</option>
                                    <option value="consultant">Consultant</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email (Opsional)</label>
                                <input type="email" name="email" class="form-control" id="email">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contact" class="form-label">Contact (Opsional)</label>
                                <input type="text" name="contact" class="form-control" id="contact">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="aniversary" class="form-label">Aniversary (Opsional)</label>
                                <input type="date" name="aniversary" class="form-control" id="aniversary">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="media_sosial" class="form-label">Media Sosial (Opsional)</label>
                                <input type="text" name="media_sosial" class="form-control" id="media_sosial">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="alamat" class="form-label">Alamat (Opsional)</label>
                                <input type="text" name="alamat" class="form-control" id="alamat">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="kota" class="form-label">Kota (Opsional)</label>
                                <input type="text" name="kota" class="form-control" id="kota">
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
        function openEditCustomerModal(customer) {
            // Set the form action URL dynamically for the edit form
            const form = document.getElementById('editCustomerForm');
            form.action = `/customer/${customer.id}`; // Replace with your route for updating customer

            // Populate the form fields with the customer's data
            document.getElementById('editCustomerModal').querySelector('input[name="nama"]').value = customer.nama;
            document.getElementById('editCustomerModal').querySelector('select[name="jenis_customer"]').value = customer
                .jenis_customer;
            document.getElementById('editCustomerModal').querySelector('input[name="email"]').value = customer.email ||
                '';
            document.getElementById('editCustomerModal').querySelector('input[name="contact"]').value = customer
                .contact || '';
            document.getElementById('editCustomerModal').querySelector('input[name="aniversary"]').value = customer
                .aniversary || '';
            document.getElementById('editCustomerModal').querySelector('input[name="media_sosial"]').value = customer
                .media_sosial || '';
            document.getElementById('editCustomerModal').querySelector('input[name="alamat"]').value = customer
                .alamat || '';
            document.getElementById('editCustomerModal').querySelector('input[name="kota"]').value = customer.kota ||
                '';

            // Open the edit modal
            new bootstrap.Modal(document.getElementById('editCustomerModal')).show();
        }
    </script>


</x-dashboard>
