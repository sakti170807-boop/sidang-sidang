<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? 'Riwayat Sidang' }}</title>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        
        .header p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #666;
        }
        
        .info-section {
            margin-bottom: 20px;
        }
        
        .info-section table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .info-section th,
        .info-section td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        
        .info-section th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        
        .table-section {
            margin-top: 20px;
        }
        
        .table-section h2 {
            font-size: 14px;
            margin-bottom: 10px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            padding: 6px 8px;
            text-align: left;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 11px;
        }
        
        td {
            font-size: 10px;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
            color: #666;
        }
        
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
        }
        
        .signature-box {
            width: 200px;
            text-align: center;
        }
        
        .signature-line {
            margin-top: 40px;
            padding-top: 5px;
            border-top: 1px solid #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN RIWAYAT SIDANG</h1>
        <p>Dicetak pada: {{ $date ?? date('d F Y') }}</p>
        <p>Filter: {{ $filters ?? 'Semua Data' }}</p>
    </div>

    <div class="table-section">
        <h2>DAFTAR RIWAYAT SIDANG</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Mahasiswa</th>
                    <th>NIM</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Peran</th>
                    <th>Ruangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayats as $index => $riwayat)
                    @php
                        $dosenId = auth()->id();
                        $myPenguji = $riwayat->penguji->firstWhere('dosen_id', $dosenId);
                        $myPembimbing = null;
                        
                        // Check if there are pembimbing records
                        if ($riwayat->pendaftaran && $riwayat->pendaftaran->pembimbing) {
                            $myPembimbing = $riwayat->pendaftaran->pembimbing->firstWhere('dosen_id', $dosenId);
                        }
                        
                        $peran = '';
                        if ($myPenguji) {
                            $peran .= 'Penguji ' . ucfirst($myPenguji->peran ?? '');
                        }
                        if ($myPembimbing) {
                            $peran .= $myPenguji ? ', ' : '';
                            $peran .= 'Pembimbing ' . ucfirst($myPembimbing->jenis ?? '');
                        }
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($riwayat->tanggal ?? $riwayat->created_at)->format('d/m/Y') }}</td>
                        <td>{{ $riwayat->pendaftaran->mahasiswa->name ?? '-' }}</td>
                        <td>{{ $riwayat->pendaftaran->mahasiswa->nim ?? '-' }}</td>
                        <td>{{ $riwayat->pendaftaran->judul ?? '-' }}</td>
                        <td>{{ $riwayat->pendaftaran->kategoriSidang->nama ?? '-' }}</td>
                        <td>{{ $peran }}</td>
                        <td>{{ $riwayat->ruangSidang->nama ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data riwayat sidang</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Dicetak oleh: {{ auth()->user()->name }}</p>
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
    </div>
</body>
</html>