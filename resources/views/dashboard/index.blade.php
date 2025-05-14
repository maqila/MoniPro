<x-dashboard>
    <!-- Line Chart Section for Collaborations -->
    <div class="row">
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Monthly Collaboration Overview</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <canvas id="collaborationChart" data-months='@json($collaborations->pluck('month_year'))'
                            data-counts='@json($collaborations->pluck('count'))'>
                        </canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pie Chart Section for Customer Types -->
        <div class="col-12 col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Customer Types Distribution</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <canvas id="customerTypeChart" data-types='@json($customerTypes->keys())'
                            data-counts='@json($customerTypes->values())'>
                        </canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                            <div class="stats-icon blue mb-2">
                                <i class="iconly-boldProfile"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Customers</h6>
                            <h6 class="font-extrabold mb-0">{{ $customerCount }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                            <div class="stats-icon blue mb-2">
                                <i class="iconly-boldBookmark"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Timeline</h6>
                            <h6 class="font-extrabold mb-0">{{ $timelineCount }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="basic-table">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Top Timeline</h4>
                </div>
                <div class="card-content">
                    <!-- Table with no outer spacing -->
                    <div class="table-responsive">
                        <table class="table table-hover table-lg">
                            <thead>
                                <tr>
                                    <th>Keterangan</th>
                                    <th>Tanggal</th>
                                    <th>Deadline</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topTimelines as $timeline)
                                    <tr>
                                        <td>{{ $timeline->keterangan }}</td>
                                        <td>{{ \Carbon\Carbon::parse($timeline->tanggal)->translatedFormat('d F Y') }}
                                        </td>
                                        <td>
                                            @if ($timeline->days_remaining >= 0)
                                                {{ $timeline->days_remaining }} Days Remaining
                                            @else
                                                {{ abs($timeline->days_remaining) }} Days Overdue
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Top Customer</h4>
                </div>
                <div class="card-content">
                    <!-- Table with no outer spacing -->
                    <div class="table-responsive">
                        <table class="table table-hover table-lg">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Last Kerjasama</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topCustomers as $customer)
                                    <tr>
                                        <td>{{ $customer->nama }}</td>
                                        <td>{{ \Carbon\Carbon::parse($customer->last_kerjasama)->translatedFormat('d F Y') }}
                                        </td>
                                        <td class="col-3">
                                            <div class="d-flex align-items-center">
                                                <p class="font-bold ms-2 mb-0">{{ $customer->status }}</p>
                                            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/collaboration_chart.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/collaboration_chart.js') }}"></script>
    <script src="{{ asset('js/customer_type_chart.js') }}"></script>
</x-dashboard>
