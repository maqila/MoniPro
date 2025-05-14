<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Customer Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            /* Slightly reduced font size */
            margin: 0px;
            /* Further reduced margin */
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 8px;
            /* Further reduced margin bottom */
        }

        .header h2 {
            margin-bottom: 4px;
            font-size: 15px;
            /* Reduced header font size */
        }

        .info-section {
            margin-bottom: 8px;
        }

        .info-section p {
            margin: 3px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            word-wrap: break-word;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 4px;
            /* Further reduced padding */
            font-size: 10px;
            /* Further reduced font size for table cells */
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>Details for Customer: {{ $customer->nama }}</h2>
    </div>

    <!-- Customer Information Section -->
    <div class="info-section">
        <p><strong>Email:</strong> {{ $customer->email }}</p>
        <p><strong>Contact:</strong> {{ $customer->contact }}</p>
        <p><strong>Jenis Customer:</strong> {{ ucfirst($customer->jenis_customer) }}</p>
        <p><strong>Last Kerjasama:</strong>
            {{ $customer->last_kerjasama ? \Carbon\Carbon::parse($customer->last_kerjasama)->translatedFormat('d F Y') : 'No data' }}
        </p>
        <p><strong>Status:</strong> {{ ucfirst($customer->status) }}</p>
    </div>

    <!-- Collaborations Table Section -->
    <h3>Collaborations</h3>
    <table class="table">
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
                <th>Dokumen</th>
                <th>PIC SE</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($collaborations as $collaboration)
                <tr>
                    <td>{{ $collaboration->kode }}</td>
                    <td>{{ $collaboration->nama_proyek }}</td>
                    <td>{{ $collaboration->pic }}</td>
                    <td>{{ $collaboration->jabatan }}</td>
                    <td>{{ $collaboration->contact }}</td>
                    <td>{{ \Carbon\Carbon::parse($collaboration->tanggal)->translatedFormat('d F Y') }}</td>
                    <td>{{ $collaboration->kepatuhan_pembayaran_text }}</td>
                    <td>{{ $collaboration->komitmen_kontrak_text }}</td>
                    <td>{{ $collaboration->respon_komunikasi_text }}</td>
                    <td>{{ $collaboration->pengambilan_keputusan_text }}</td>
                    <td>
                        @if ($collaboration->dokumen)
                            <a href="{{ asset('dokumenCollabs/' . $collaboration->dokumen) }}"
                                download>{{ $collaboration->dokumen }}</a>
                        @else
                            No Document
                        @endif
                    </td>
                    <td>{{ $collaboration->pic_se }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center">No collaborations found for this customer.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>
