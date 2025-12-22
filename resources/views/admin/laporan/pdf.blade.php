<!DOCTYPE html>
<html>
<head>
    <title>Laporan Sidang - {{ $startDate }} sampai {{ $endDate }}</title>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 15mm;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 16px;
            color: #333;
        }
        
        .header p {
            margin: 3px 0 0 0;
            font-size: 11px;
            color: #666;
        }
        
        .info-section {
            margin-bottom: 15px;
        }
        
        .info-section table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .info-section th,
        .info-section td {
            padding: 6px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        
        .info-section th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        
        .table-section {
            margin-top: 15px;
        }
        
        .table-section h2 {
            font-size: 13px;
            margin-bottom: 8px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        th, td {
            padding: 5px 6px;
            text-align: left;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 9px;
        }
        
        td {
            font-size: 8px;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 9px;
            color: #666;
        }
        
        .signature-section {
            margin-top: 30px;
            text-align: right;
        }
        
        .signature-box {
            display: inline-block;
            width: 180px;
            text-align: center;
        }
        
        .signature-line {
            margin-top: 35px;
            padding-top: 3px;
            border-top: 1px solid #333;
        }
        
        /* Page break untuk data banyak */
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN SIDANG</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</p>
    </div>

    <div class="info-section">
        <table>
            <tr>
                <th>Total Mahasiswa</th>
                <td class="text-center">{{ $jadwals->pluck('pendaftaran.mahasiswa.id')->unique()->count() }}</td>
                <th>Total Dosen</th>
                <td class="text-center">{{ $jadwals->flatMap(fn($j) => $j->penguji->pluck('dosen_id'))->unique()->count() }}</td>
            </tr>
            <tr>
                <th>Total Sidang</th>
                <td class="text-center">{{ $jadwals->count() }}</td>
                <th>Sidang Selesai</th>
                <td class="text-center">{{ $jadwals->whereIn('status', ['selesai', 'completed'])->count() }}</td>
            </tr>
        </table>
    </div>

    <div class="table-section">
        <h2>DAFTAR SIDANG</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 3%;">No</th>
                    <th style="width: 10%;">Tanggal</th>
                    <th style="width: 18%;">Mahasiswa</th>
                    <th style="width: 10%;">NIM</th>
                    <th style="width: 15%;">Kategori</th>
                    <th style="width: 12%;">Ruangan</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 8%;">Nilai</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwals as $index => $jadwal)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d/m/Y H:i') }}</td>
                        <td>{{ $jadwal->pendaftaran->mahasiswa->name ?? '-' }}</td>
                        <td>{{ $jadwal->pendaftaran->mahasiswa->nim ?? '-' }}</td>
                        <td>{{ $jadwal->pendaftaran->kategoriSidang->nama ?? '-' }}</td>
                        <td>{{ $jadwal->ruangSidang->nama ?? '-' }}</td>
                        <td>{{ ucfirst($jadwal->status) }}</td>
                        <td class="text-center">
                            @php
                                $nilaiList = $jadwal->penguji->map(fn($p) => $p->penilaian?->nilai_akhir)->filter();
                                $nilaiRataRata = $nilaiList->count() > 0 ? round($nilaiList->average(), 2) : '-';
                            @endphp
                            {{ $nilaiRataRata }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data sidang</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p>Mengetahui,</p>
            <div class="signature-line">Admin Sidang</div>
        </div>
    </div>
</body>
</html>