<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Export Kolaborasi - {{ $customer->nama }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
            padding: 5px;
        }

        th {
            background-color: #eee;
        }

        .info-section {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <h2>Data Customer: {{ $customer->nama }}</h2>

    @php
        $bulan = request('month');
        $tahun = request('year') ?: now()->year;

        $namaBulan =
            $bulan && is_numeric($bulan)
                ? \Carbon\Carbon::createFromDate($tahun, $bulan)->translatedFormat('F')
                : 'Semua Bulan';

        $namaTahun = request('year') ?: 'Semua Tahun';
    @endphp

    <p><strong>Periode:</strong> {{ $namaBulan }} {{ $namaTahun }}</p>

    <!-- Customer Info -->
    <div class="info-section">
        <p><strong>Email:</strong> {{ $customer->email }}</p>
        <p><strong>Contact:</strong> {{ $customer->contact }}</p>
        <p><strong>Jenis Customer:</strong> {{ ucfirst($customer->jenis_customer) }}</p>
        <p><strong>Last Kerjasama:</strong>
            {{ $customer->last_kerjasama ? \Carbon\Carbon::parse($customer->last_kerjasama)->translatedFormat('d F Y') : 'No data' }}
        </p>
        <p><strong>Status:</strong> {{ ucfirst($customer->status) }}</p>
    </div>

    <!-- Table of Collaborations -->
    <h3>Collaborations</h3>
    <table>
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
            @forelse ($collaborations as $c)
                <tr>
                    <td>{{ $c->kode }}</td>
                    <td>{{ $c->nama_proyek }}</td>
                    <td>{{ $c->pic }}</td>
                    <td>{{ $c->jabatan }}</td>
                    <td>{{ $c->contact }}</td>
                    <td>{{ \Carbon\Carbon::parse($c->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $c->kepatuhan_pembayaran_text }}</td>
                    <td>{{ $c->komitmen_kontrak_text }}</td>
                    <td>{{ $c->respon_komunikasi_text }}</td>
                    <td>{{ $c->pengambilan_keputusan_text }}</td>
                    <td>{{ $c->dokumen ?? 'No file' }}</td>
                    <td>{{ $c->pic_se }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" style="text-align: center;">Tidak ada kolaborasi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>
