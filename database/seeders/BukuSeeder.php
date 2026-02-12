<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\KategoriBuku;
use Illuminate\Database\Seeder;

class BukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. BUAT KATEGORI DULU (Simpan ke variabel agar bisa diambil ID-nya)
        $catNovel = KategoriBuku::create(['nama_kategori' => 'Novel']);
        $catTekno = KategoriBuku::create(['nama_kategori' => 'Teknologi']);
        $catBisnis = KategoriBuku::create(['nama_kategori' => 'Bisnis & Ekonomi']);
        $catSelf = KategoriBuku::create(['nama_kategori' => 'Pengembangan Diri']);
        $catKomik = KategoriBuku::create(['nama_kategori' => 'Komik']);

        // 2. BUAT DATA BUKU DUMMY
        // Kita biarkan 'gambar' => null agar CSS Placeholder yang kita buat sebelumnya muncul.

        $bukus = [
            [
                'judul' => 'Laskar Pelangi',
                'penulis' => 'Andrea Hirata',
                'penerbit' => 'Bentang Pustaka',
                'tahun' => '2005',
                'stok' => 12,
                'kategori_id' => $catNovel->id,
                'sinopsis' => 'Kisah perjuangan sepuluh anak Belitong yang bersekolah di sebuah SD Muhammadiyah yang hampir roboh. Penuh inspirasi, tawa, dan tangis.',
            ],
            [
                'judul' => 'Atomic Habits',
                'penulis' => 'James Clear',
                'penerbit' => 'Gramedia Pustaka Utama',
                'tahun' => '2019',
                'stok' => 25,
                'kategori_id' => $catSelf->id,
                'sinopsis' => 'Perubahan kecil yang memberikan hasil luar biasa. Buku ini mengajarkan cara membangun kebiasaan baik dan membuang kebiasaan buruk.',
            ],
            [
                'judul' => 'Clean Code',
                'penulis' => 'Robert C. Martin',
                'penerbit' => 'Prentice Hall',
                'tahun' => '2008',
                'stok' => 5,
                'kategori_id' => $catTekno->id,
                'sinopsis' => 'Panduan wajib bagi para programmer untuk menulis kode yang bersih, mudah dibaca, dan mudah dipelihara.',
            ],
            [
                'judul' => 'Filosofi Teras',
                'penulis' => 'Henry Manampiring',
                'penerbit' => 'Kompas',
                'tahun' => '2018',
                'stok' => 0, // Stok habis untuk tes label merah
                'kategori_id' => $catSelf->id,
                'sinopsis' => 'Penjelasan filsafat Stoisisme yang relevan dengan kehidupan masa kini. Mengajarkan cara hidup tenang di tengah kekhawatiran.',
            ],
            [
                'judul' => 'Naruto Vol. 72',
                'penulis' => 'Masashi Kishimoto',
                'penerbit' => 'Elex Media Komputindo',
                'tahun' => '2015',
                'stok' => 50,
                'kategori_id' => $catKomik->id,
                'sinopsis' => 'Volume terakhir dari kisah epik Naruto Uzumaki. Pertarungan akhir melawan Kaguya dan penentuan nasib dunia ninja.',
            ],
            [
                'judul' => 'Rich Dad Poor Dad',
                'penulis' => 'Robert T. Kiyosaki',
                'penerbit' => 'Gramedia Pustaka Utama',
                'tahun' => '2017',
                'stok' => 8,
                'kategori_id' => $catBisnis->id,
                'sinopsis' => 'Buku pengelolaan keuangan pribadi nomor 1 sepanjang masa. Mengubah pola pikir tentang uang dan investasi.',
            ],
            [
                'judul' => 'Bumi',
                'penulis' => 'Tere Liye',
                'penerbit' => 'Gramedia Pustaka Utama',
                'tahun' => '2014',
                'stok' => 15,
                'kategori_id' => $catNovel->id,
                'sinopsis' => 'Petualangan Raib, Seli, dan Ali ke dunia paralel. Awal dari serial "Bumi" yang sangat populer di kalangan remaja.',
            ],
            [
                'judul' => 'Laravel: Up & Running',
                'penulis' => 'Matt Stauffer',
                'penerbit' => 'O Reilly Media',
                'tahun' => '2019',
                'stok' => 3,
                'kategori_id' => $catTekno->id,
                'sinopsis' => 'Panduan komprehensif untuk menguasai framework PHP terpopuler, Laravel. Cocok untuk pemula hingga mahir.',
            ],
        ];

        foreach ($bukus as $buku) {
            Buku::create($buku);
        }
    }
}
