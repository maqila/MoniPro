<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Export Kolaborasi</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
            padding: 4px;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>

<body>
    <h3>Laporan Kerjasama</h3>
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

    <table>
        <thead>
            <tr>
                <th>Customer</th>
                <th>Kode</th>
                <th>Proyek</th>
                <th>PIC</th>
                <th>Jabatan</th>
                <th>Contact</th>
                <th>Tanggal</th>
                <th>Kepatuhan Pembayaran</th>
                <th>Nilai Total Pembayaran</th>
                <th>Respon Komunikasi</th>
                <th>Jangka Waktu Pembayaran Pada Kontrak</th>
                <th>Status</th>
                <th>PIC SE</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($collaborations as $c)
                <tr>
                    <td>{{ $c->customer->nama }}</td>
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
                    <td>{{ $c->status }}</td>
                    <td>{{ $c->pic_se }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13">Tidak ada data kolaborasi</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
