# Dokumentasi Sistem Perpustakaan Berbasis Website

Dokumentasi ini menjelaskan secara komprehensif alur kerja website perpustakaan dari awal (pendaftaran pengguna) hingga akhir (pengembalian buku dan laporan), beserta otomatisasi yang ada di dalamnya.

## 1. Hak Akses (Role)
Sistem memiliki dua jenis pengguna:
- **Admin**: Memiliki kendali penuh terhadap master data (buku, kategori, penulis, penerbit, pengguna), pengaturan denda, dan manajemen transaksi peminjaman.
- **User (Anggota)**: Dapat melihat katalog buku, meminjam buku, melihat riwayat transaksi, dan menerima notifikasi dari sistem.

## 2. Alur Pencarian & Peminjaman Buku
Alur ini berawal dari User yang mencari dan ingin meminjam buku.

1. **Eksplorasi Katalog**: User login, masuk ke halaman `/home/buku`. User dapat memfilter buku berdasarkan kategori, penulis, penerbit, maupun mencari dari judul.
2. **Permohonan Pinjam**: User menekan tombol pinjam pada buku.
   - Sistem akan memvalidasi apakah stok buku masih tersedia.
   - Sistem memvalidasi apakah user sedang memiliki permohonan peminjaman untuk buku yang sama untuk mencegah duplikasi (belum disetujui).
   - Jika valid, transaksi dibuat dengan status `menunggu_konfirmasi`.
   - **Stok buku otomatis berkurang 1** (sistem booking agar buku tidak dipinjam/direbut orang lain selagi menunggu konfirmasi admin).

## 3. Alur Konfirmasi Admin
Admin meninjau daftar transaksi di halaman manajemen transaksi.
1. **Persetujuan Peminjaman**: Admin menyetujui peminjaman (tombol Setujui).
   - Status transaksi berubah menjadi `siap_diambil`.
   - Sistem mencatat waktu di kolom `siap_diambil_at`.
   - **Notifikasi**: Sistem mengirimkan notifikasi ke akun User bahwa buku siap diambil di perpustakaan.
2. **Penolakan Peminjaman**: Jika admin menolak (misalnya karena kondisi fisik buku tidak layak atau dipesan untuk acara tertentu).
   - Status transaksi berubah menjadi `ditolak`. 
   - Stok buku akan ditambahkan kembali sebanyak 1 ke database secara otomatis.

## 4. Alur Pengambilan & Masa Peminjaman
Saat User datang ke perpustakaan untuk mengambil buku fisik:
1. **Konfirmasi Pengambilan**: Admin menekan tombol "Konfirmasi Pengambilan" setelah menyerahkan fisik buku ke User.
   - Status transaksi berubah menjadi `dipinjam`.
   - Waktu mulai pinjam (`tanggal_pinjam`) dicatat *tepat pada saat ini*, bukan saat User mengklik di website.
   - Waktu jatuh tempo diset secara dinamis berdasarkan nilai dari pengaturan sistem (contoh: 7 hari dari sekarang).
   - **Notifikasi**: User mendapat notifikasi "Buku Dipinjam" bahwa buku telah resmi dipinjam beserta informasi tanggal jatuh temponya.

## 5. Fitur Otomatisasi (Cron Jobs Latar Belakang)
Sistem memiliki *Console Command* Laravel yang berjalan di latar belakang:
- **Batal Otomatis 24 Jam (`transaksi:auto-batal`)**:
  - Jika buku berstatus `siap_diambil` namun User tertunda/tidak datang dalam waktu **24 Jam** setelah disetujui oleh admin, sistem akan otomatis mengubah status menjadi `batal_otomatis`.
  - Stok buku otomatis dikembalikan agar buku bisa dipinjam oleh orang lain yang mengantre.
- **Notifikasi Terlambat (`transaksi:auto-notif-terlambat`)**:
  - Sistem akan mengecek semua transaksi berstatus `dipinjam` yang secara waktu sudah **melewati** `tanggal_jatuh_tempo`.
  - Sistem akan mengirimkan notifikasi peringatan ke User terkait denda keterlambatan.

## 6. Alur Pengembalian Buku, Denda & Status Hilang
Saat masa pinjam selesai:
1. **Buku Dikembalikan Tepat Waktu**:
   - User mengembalikan buku fisik ke Admin. Admin menekan tombol "Kembalikan".
   - Status menjadi `dikembalikan`. Stok buku masuk kembali (+1). Denda tercatat = Rp 0.
2. **Buku Terlambat**:
   - Jika waktu saat Admin menekan "Kembalikan" telah melewati `tanggal_jatuh_tempo`, sistem akan menghitung selisih hari.
   - **Denda** = Selisih Hari Keterlambatan × Tarif Denda Per Hari (yang diatur Admin di tabel `pengaturan`).
   - Sistem menetapkan denda yang harus dibayar pada transaksi tersebut dan menormalkan kembali stok buku (+1).
3. **Buku Hilang**:
   - Jika User melapor bahwa buku hilang, Admin menekan tombol "Hilang".
   - Status langsung menjadi `hilang`. Denda yang diterapkan mengambil nilai tetap dari "Denda Buku Hilang" (diatur di bagian pengaturan sistem Admin).
   - Stok buku *tidak* bertambah.

## 7. Pelaporan (Reporting)
Admin dapat mencetak laporan performa dan log peminjaman untuk diarsipkan.
- **Laporan Aktivitas User (PDF)**: Admin dapat membuka profil dari seorang user dan mengunduh riwayat spesifik dari user tersebut (file PDF berisi tabel lengkap riwayat peminjaman user ybs).
- **Laporan Transaksi Umum (Excel)**: Pada halaman laporan utama, Admin mengeklik "Export Excel" untuk mengunduh seluruh basis data transaksi beserta relasi buku dan usernya ke dalam format tabel `.xlsx`.

---
*Dokumentasi ini dirangkum berdasarkan arsitektur model, rute, dan controller dari sistem versi terkini.*
