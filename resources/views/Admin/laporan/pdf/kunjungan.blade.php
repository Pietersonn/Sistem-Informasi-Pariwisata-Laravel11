<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kunjungan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .stats {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stat-item {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
        }
        .stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
        }
        .stat-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KUNJUNGAN WISATA</h1>
        <p>Sistem Informasi Wisata</p>
        @if($tanggalMulai && $tanggalSelesai)
            <p>Periode: {{ date('d/m/Y', strtotime($tanggalMulai)) }} - {{ date('d/m/Y', strtotime($tanggalSelesai)) }}</p>
        @endif
        <p>Tanggal Cetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="stats">
        <div class="stat-item">
            <div class="stat-number">{{ number_format($statistik['total_kunjungan']) }}</div>
            <div class="stat-label">Total Kunjungan</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ number_format($statistik['rata_rata_kunjungan'], 0) }}</div>
            <div class="stat-label">Rata-rata Kunjungan</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $topKunjungan->count() }}</div>
            <div class="stat-label">Wisata Terpopuler</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">Ranking</th>
                <th>Nama Wisata</th>
                <th>Alamat</th>
                <th class="text-center">Jumlah Dilihat</th>
                <th class="text-center">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topKunjungan as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ Str::limit($item->alamat, 50) }}</td>
                <td class="text-center">{{ number_format($item->jumlah_dilihat) }}</td>
                <td class="text-center">
                    {{ $statistik['total_kunjungan'] > 0 ? number_format(($item->jumlah_dilihat / $statistik['total_kunjungan']) * 100, 2) : 0 }}%
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px;">
        <h3>Ringkasan Kunjungan</h3>
        <p><strong>Wisata Paling Populer:</strong> {{ $statistik['wisata_terbanyak_dilihat'] }}</p>
        <p><strong>Total Kunjungan:</strong> {{ number_format($statistik['total_kunjungan']) }} kali dilihat</p>
        <p><strong>Rata-rata Kunjungan per Wisata:</strong> {{ number_format($statistik['rata_rata_kunjungan'], 0) }} kali dilihat</p>
    </div>

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem pada {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>