<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jurnal KBM</title>
    <style>
        * {
            margin: 0px 0px;
            padding: 0px 0px;
        }

        body {
            padding: 25px 25px;
        }

        h1 {
            text-align: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #2099ff;
            font-size: 24px;
        }

        table.header {
            margin: 25px 0px 50px 0px;
        }

        table.header tbody tr td.header-left,
        table.header tbody tr td.header-right {
            padding: 0px 0px 0px 50px;
        }

        table.header tbody tr td.header-left img {
            width: 200px;
            height: 200px;
            border-radius: 100%;
            border: 5px solid #2099ff;
        }

        table.header tbody tr td.header-right table tbody tr td {
            padding: 0px 0px 5px 0px;
            font-size: 18px;
        }
        
        table.header tbody tr td.header-right table tbody tr td:first-child {
            font-weight: bold;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        table.header tbody tr td.header-right table tbody tr td:nth-child(2) {
            font-weight: bold;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            width: 50px;
            text-align: center;
        }
        
        table.header tbody tr td.header-right table tbody tr td:last-child {
            font-weight: bold;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        table.main {
            width: 100%;
            border-collapse: collapse;
        }

        table.main thead tr th {
            border: 2px solid black;
            background-color: #2099ff;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            padding: 5px 5px;
        }
        
        table.main tbody tr td {
            border: 2px solid black;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            text-align: start;
            padding: 5px 5px 5px 5px;
            text-align: center;
        }
        
        table.main tbody tr td img {
            width: 200px;
            height: 115px;
            object-fit: cover;
            border-radius: 5px;
        }

        table.main tbody tr td:nth-child(2) {
            width: 95px;
        }

        table.main tbody tr td:nth-child(3) {
            width: 85px;
        }

        table.main tbody tr td:nth-child(4) {
            width: 75px;
        }

        table.main tbody tr td:nth-child(5) {
            width: 115px;
        }

        table.main tbody tr td:nth-child(6) {
            width: 125px;
        }

        table.main tbody tr td:nth-child(7) {
            width: 125px;
        }

        table.main tbody tr td:nth-child(8) {
            width: 125px;
        }
    </style>
</head>
<body>
    <h1>Jurnal Kegiatan Belajar Mengajar</h1>
    <table class="header">
        <tbody>
            <tr>
                <td class="header-left">
                    <img src="https://jurnal.eclipse.my.id/storage/profile-picture/{{ $user->foto_profil }}" alt="{{ $user->foto_profil }}">
                </td>
                <td class="header-right">
                    <table>
                        <tbody>
                            <tr>
                                <td>Nama</td><td>:</td><td>{{ $user->nama }}</td>
                            </tr>
                            <tr>
                                <td>NIP</td><td>:</td><td>{{ $user->nip }}</td>
                            </tr>
                            <tr>
                                <td>Mata Pelajaran</td><td>:</td><td>{{ $user->mata_pelajaran }}</td>
                            </tr>
                            <tr>
                                <td>Sekolah</td><td>:</td><td>{{ $user->sekolah }}</td>
                            </tr>
                            <tr>
                                <td>Semester</td><td>:</td><td>{{ $semester }}</td>
                            </tr>
                            <tr>
                                <td>Tahun Pembelajaran</td><td>:</td><td>{{ $tahun_pembelajaran }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="main">
        <thead>
            <tr>
                <th>No</th>
                <th>Hari / Tanggal</th>
                <th>Jam Pembelajaran</th>
                <th>Kelas</th>
                <th>Kehadiran</th>
                <th>Uraian Kegiatan</th>
                <th>Materi</th>
                <th>Tujuan Pembelajaran</th>
                <th>Foto Kegiatan</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($jurnal as $index => $j)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($j->hari_tanggal)->translatedFormat('l, d F Y') }}</td>
                <td>{{ $j->jam_pembelajaran }}</td>
                <td>{{ $j->kelas }}</td>
                <td>{{ $j->kehadiran }}</td>
                <td>{{ $j->uraian_kegiatan }}</td>
                <td>{{ $j->materi }}</td>
                <td>{{ $j->tujuan_pembelajaran }}</td>
                <td><img src="https://jurnal.eclipse.my.id/storage/activity-photos/{{ $j->foto_kegiatan }}" alt="{{ $j->foto_kegiatan }}"></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>