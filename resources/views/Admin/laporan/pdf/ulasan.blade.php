<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Ulasan</title>
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
            width: 25%;
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
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            color: white;
        }
        .badge-success { background-color: #28a745; }
        .badge-secondary { background-color: #6c757d; }
        .badge-warning { background-color: #ffc107; color: #212529; }
        .stars {
            color: #ffc107;
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
        <h1>LAPORAN ULASAN</h1>
        <p>Sistem Informasi Wisata</p>
        @if($tanggalMulai && $tanggalSelesai)
            <p>Periode: {{ date('d/m/Y', strtotime($tanggalMulai)) }} - {{ date('d/m/Y', strtotime($tanggalSelesai)) }}</p>
        @endif
        @if($statusTerpilih)
            <p>Status: {{ ucfirst(str_replace('_', ' ', $statusTerpilih)) }}</p>
        @endif
        <p>Tanggal Cetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="stats">
        <div class="stat-item">
            <div class="stat-number">{{ $statistik['total_ulasan'] }}</div>
            <div class="stat-label">Total Ulasan</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $statistik['ulasan_ditampilkan'] }}</div>
            <div class="stat-label">Ditampilkan</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $statistik['ulasan_disembunyikan'] }}</div>
            <div class="stat-label">Disembunyikan</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $statistik['ulasan_menunggu'] }}</div>
            <div class="stat-label">Menunggu Moderasi</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Wisata</th>
                <th>Pengunjung</th>
                <th>Rating</th>
                <th>Komentar</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ulasan as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->wisata->nama }}</td>
                <td>{{ $item->pengguna->name }}</td>
                <td>
                    <span class="stars">
                        @for($i = 1; $i <= 5; $i++)
                            {{ $i <= $item->rating ? '★' : '☆' }}
                        @endfor
                    </span>
                </td>
                <td>{{ Str::limit($item->komentar, 100) }}</td>
                <td>
                    <span class="badge badge-{{ $item->status == 'ditampilkan' ? 'success' : ($item->status == 'disembunyikan' ? 'secondary' : 'warning') }}">
                        {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                    </span>
                </td>
                <td>{{ $item->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem pada {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>