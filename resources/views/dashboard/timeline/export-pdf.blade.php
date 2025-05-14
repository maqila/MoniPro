<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Timeline</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
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
    </style>
</head>

<body>
    <h3 style="text-align: center;">Laporan Timeline</h3>
    <table>
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
                    <td>{{ \Carbon\Carbon::parse($timeline->tanggal)->translatedFormat('d-m-Y') }}</td>
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
                        @elseif ($timeline->days_remaining >= 0)
                            {{ $timeline->days_remaining }} hari lagi
                        @else
                            Telat {{ abs($timeline->days_remaining) }} hari
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
