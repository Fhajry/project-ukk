<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\Penerbit;
use App\Models\Penulis;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http; // Ganti jadi Kategori jika modelmu namanya Kategori.php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BukuSeeder extends Seeder
{
    public function run()
    {
        // Agar script tidak timeout saat mendownload 100 gambar
        set_time_limit(0);

        $this->command->info('Mulai menarik data buku asli dan mendownload gambar... (Ini butuh waktu 1-3 menit)');

        // Kata kunci pencarian agar genre bukunya bervariasi
        $queries = ['pemrograman', 'novel fiksi', 'sejarah indonesia', 'bisnis digital', 'psikologi'];
        $count = 0;

        // Pastikan folder buku ada
        if (! Storage::exists('public/buku')) {
            Storage::makeDirectory('public/buku');
        }

        foreach ($queries as $query) {
            // Mengambil 20 buku per kata kunci dari Google Books API
            $response = Http::withoutVerifying()->get('https://www.googleapis.com/books/v1/volumes', [
                'q' => $query,
                'maxResults' => 20,
                'langRestrict' => 'id',
            ]);

            if ($response->successful()) {
                $items = $response->json('items') ?? [];

                foreach ($items as $item) {
                    if ($count >= 100) {
                        break;
                    } // Berhenti jika sudah 100 buku

                    $volumeInfo = $item['volumeInfo'];

                    // Lewati jika buku ini tidak punya judul atau tidak punya gambar sampul
                    if (empty($volumeInfo['title']) || empty($volumeInfo['imageLinks']['thumbnail'])) {
                        continue;
                    }

                    // 1. Simpan/Ambil Kategori
                    $kategoriName = $volumeInfo['categories'][0] ?? ucfirst($query);
                    $kategori = KategoriBuku::firstOrCreate(['nama_kategori' => substr($kategoriName, 0, 100)]);

                    // 2. Simpan/Ambil Penulis
                    $penulisName = $volumeInfo['authors'][0] ?? 'Anonim';
                    $penulis = Penulis::firstOrCreate(['nama_penulis' => substr($penulisName, 0, 100)]);

                    // 3. Simpan/Ambil Penerbit
                    $penerbitName = $volumeInfo['publisher'] ?? 'Penerbit Independen';
                    $penerbit = Penerbit::firstOrCreate(['nama_penerbit' => substr($penerbitName, 0, 100)]);

                    // Cek apakah buku sudah ada agar tidak duplikat
                    $judulBuku = substr($volumeInfo['title'], 0, 255);
                    if (Buku::where('judul', $judulBuku)->exists()) {
                        continue;
                    }

                    // 4. Download Foto Sampul (Cover)
                    $imageUrl = $volumeInfo['imageLinks']['thumbnail'];
                    $imageUrl = str_replace('http:', 'https:', $imageUrl); // Google API kadang pakai http
                    $imageName = time().'_'.Str::slug(substr($judulBuku, 0, 30)).'.jpg';

                    try {
                        $imageContents = Http::withoutVerifying()->get($imageUrl)->body();
                        Storage::put('public/buku/'.$imageName, $imageContents);
                    } catch (\Exception $e) {
                        $imageName = null; // Jika gagal download, biarkan kosong
                    }

                    // 5. Simpan ke Tabel Buku
                    Buku::create([
                        'judul' => $judulBuku,
                        'penulis_id' => $penulis->id,
                        'penerbit_id' => $penerbit->id,
                        'kategori_id' => $kategori->id,
                        'tahun' => isset($volumeInfo['publishedDate']) ? (int) substr($volumeInfo['publishedDate'], 0, 4) : rand(2010, 2024),
                        'stok' => rand(5, 50),
                        'gambar' => $imageName ? 'buku/'.$imageName : null,
                        'sinopsis' => $volumeInfo['description'] ?? 'Tidak ada sinopsis untuk buku ini.',
                    ]);

                    $count++;
                    $this->command->info("Tersimpan [{$count}/100]: {$judulBuku}");
                }
            }
        }

        $this->command->info("Selesai! {$count} Buku asli beserta foto covernya berhasil ditambahkan.");
    }
}
