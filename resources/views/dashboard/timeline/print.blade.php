<!DOCTYPE html>
<html>

<head>
    <title>Cetak Timeline</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container my-4">
        <h2 class="mb-3">Laporan Timeline</h2>
        <table class="table table-bordered table-striped">
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
                </tr>
            </thead>
            <tbody>
                @foreach ($timelines as $timeline)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($timeline->tanggal)->translatedFormat('d F Y') }}</td>
                        <td>{{ $timeline->jam }}</td>
                        <td>{{ $timeline->tempat }}</td>
                        <td>{{ $timeline->pic_se }}</td>
                        <td>{{ $timeline->keterangan }}</td>
                        <td>{{ $timeline->perusahaan }}</td>
                        <td>{{ $timeline->nama }}</td>
                        <td>{{ $timeline->contact }}</td>
                        <td>{{ $timeline->deadline }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button onclick="window.print()" class="btn btn-primary no-print">Print</button>
    </div>
</body>

</html>
