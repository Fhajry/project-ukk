<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>

<body>

    <table>
        <thead>
            {{-- JUDUL LAPORAN --}}
            <tr>
                <th colspan="7" style="text-align: center; font-size: 16pt; font-weight: bold;">
                    LAPORAN DATA TRANSAKSI PERPUSTAKAAN
                </th>
            </tr>
            <tr>
                <th colspan="7" style="text-align: center; font-size: 12pt;">
                    Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y H:i') }}
                </th>
            </tr>

            {{-- BARIS KOSONG --}}
            <tr>
                <th colspan="7"></th>
            </tr>

            {{-- HEADER TABEL --}}
            <tr>
                <th
                    style="background-color: #4F81BD; color: #FFFFFF; border: 1px solid #000000; font-weight: bold; text-align: center;">
                    No</th>
                <th style="background-color: #4F81BD; color: #FFFFFF; border: 1px solid #000000; font-weight: bold;">
                    Nama Peminjam</th>
                <th style="background-color: #4F81BD; color: #FFFFFF; border: 1px solid #000000; font-weight: bold;">
                    Judul Buku</th>
                <th
                    style="background-color: #4F81BD; color: #FFFFFF; border: 1px solid #000000; font-weight: bold; text-align: center;">
                    Tgl Pinjam</th>
                <th
                    style="background-color: #4F81BD; color: #FFFFFF; border: 1px solid #000000; font-weight: bold; text-align: center;">
                    Jatuh Tempo</th>
                <th
                    style="background-color: #4F81BD; color: #FFFFFF; border: 1px solid #000000; font-weight: bold; text-align: center;">
                    Tgl Kembali</th>
                <th
                    style="background-color: #4F81BD; color: #FFFFFF; border: 1px solid #000000; font-weight: bold; text-align: center;">
                    Status</th>
                <th
                    style="background-color: #4F81BD; color: #FFFFFF; border: 1px solid #000000; font-weight: bold; text-align: right;">
                    Denda (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($transaksis as $t)
            <tr>
                <td style="border: 1px solid #000000; text-align: center;">{{ $no++ }}</td>
                <td style="border: 1px solid #000000;">{{ $t->user->name }}</td>
                <td style="border: 1px solid #000000;">{{ $t->buku->judul }}</td>
                <td style="border: 1px solid #000000; text-align: center;">
                    {{ $t->tanggal_pinjam ? \Carbon\Carbon::parse($t->tanggal_pinjam)->format('d/m/Y') : '-' }}
                </td>
                <td style="border: 1px solid #000000; text-align: center;">
                    {{ $t->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($t->tanggal_jatuh_tempo)->format('d/m/Y') : '-'
                    }}
                </td>
                <td style="border: 1px solid #000000; text-align: center;">
                    {{ $t->tanggal_kembali ? \Carbon\Carbon::parse($t->tanggal_kembali)->format('d/m/Y') : '-' }}
                </td>
                <td style="border: 1px solid #000000; text-align: center;">
                    {{ ucfirst(str_replace('_', ' ', $t->status)) }}
                </td>
                <td style="border: 1px solid #000000; text-align: right;">
                    {{-- Mengirimkan angka mentah agar bisa di-SUM di Excel --}}
                    {{ $t->denda ?? 0 }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="border: 1px solid #000000; text-align: center;">Tidak ada data transaksi.</td>
            </tr>
            @endforelse
        </tbody>

        {{-- FOOTER UNTUK TOTAL --}}
        <tfoot>
            <tr>
                <th colspan="7"
                    style="border: 1px solid #000000; text-align: right; font-weight: bold; background-color: #f2f2f2;">
                    TOTAL DENDA MASUK:</th>
                <th style="border: 1px solid #000000; text-align: right; font-weight: bold; background-color: #f2f2f2;">
                    {{ $transaksis->sum('denda') }}
                </th>
            </tr>
        </tfoot>
    </table>

</body>

</html>