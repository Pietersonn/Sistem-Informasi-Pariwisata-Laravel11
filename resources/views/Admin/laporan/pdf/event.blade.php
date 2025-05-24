<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Event</title>
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
        <h1>LAPORAN EVENT WISATA</h1>
        <p>Sistem Informasi Wisata</p>
        @if($tanggalMulai && $tanggalSelesai)
            <p>Periode: {{ date('d/m/Y', strtotime($tanggalMulai)) }} - {{ date('d/m/Y', strtotime($tanggalSelesai)) }}</p>
        @endif
        @if($statusTerpilih)
            <p>Status: {{ ucfirst($statusTerpilih) }}</p>
        @endif
        <p>Tanggal Cetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="stats">
        <div class="stat-item">
            <div class="stat-number">{{ $statistik['total_event'] }}</div>
            <div class="stat-label">Total Event</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $statistik['event_aktif'] }}</div>
            <div class="stat-label">Event Aktif</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $statistik['event_selesai'] }}</div>
            <div class="stat-label">Event Selesai</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $statistik['event_dibatalkan'] }}</div>
            <div class="stat-label">Event Dibatalkan</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Event</th>
                <th>Wisata</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Status</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($event as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->wisata ? $item->wisata->nama : 'Wisata Tidak Ditemukan' }}</td>
                <td>{{ $item->tanggal_mulai instanceof \Carbon\Carbon ? $item->tanggal_mulai->format('d/m/Y') : 'Tanggal tidak tersedia' }}</td>
                <td>{{ $item->tanggal_selesai instanceof \Carbon\Carbon ? $item->tanggal_selesai->format('d/m/Y') : 'Tanggal tidak tersedia' }}</td>
                <td>
                    <span class="badge badge-{{ $item->status == 'aktif' ? 'success' : ($item->status == 'selesai' ? 'secondary' : 'warning') }}">
                        {{ ucfirst($item->status) }}
                    </span>
                </td>
                <td>{{ Str::limit($item->deskripsi, 80) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem pada {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>