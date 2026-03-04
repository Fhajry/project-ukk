<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
            color: #333;
        }

        h3 {
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .info-user {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>

    <h3>Laporan Peminjaman Buku</h3>

    <div class="info-user">
        <strong>Nama &nbsp;:</strong> {{ $user->name }} <br>
        <strong>Email &nbsp;:</strong> {{ $user->email }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Judul Buku</th>
                <th class="text-center">Pinjam</th>
                <th class="text-center">Tempo</th>
                <th class="text-center">Kembali</th>
                <th class="text-center">Status</th>
                <th class="text-center">Status Denda</th>
                <th class="text-right">Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $t)
            <tr>
                <td>{{ $t->buku->judul }}</td>

                <td class="text-center">
                    {{ $t->tanggal_pinjam ? \Carbon\Carbon::parse($t->tanggal_pinjam)->format('d-m-Y') : '-' }}
                </td>

                <td class="text-center">
                    {{ $t->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($t->tanggal_jatuh_tempo)->format('d-m-Y') : '-' }}
                </td>

                <td class="text-center">
                    {{ $t->tanggal_kembali ? \Carbon\Carbon::parse($t->tanggal_kembali)->format('d-m-Y') : '-' }}
                </td>

                <td class="text-center">
                    @php
                        $statusLabel = [
                            'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
                            'siap_diambil'        => 'Siap Diambil',
                            'dipinjam'            => 'Dipinjam',
                            'dikembalikan'        => 'Dikembalikan',
                            'ditolak'             => 'Ditolak',
                            'hilang'              => 'Hilang',
                        ];
                    @endphp
                    {{ $statusLabel[$t->status] ?? ucfirst(str_replace('_', ' ', $t->status)) }}
                </td>

                <td class="text-center">
                    @if($t->status_denda === 'lunas')
                        Lunas
                    @elseif($t->status_denda === 'belum_lunas')
                        Belum Lunas
                    @else
                        -
                    @endif
                </td>

                <td class="text-right">
                    @if($t->denda > 0)
                        Rp {{ number_format($t->denda, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Belum ada riwayat transaksi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>