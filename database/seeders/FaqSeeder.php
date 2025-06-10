<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Bagaimana cara mendaftar akun?',
                'answer' => 'Klik tombol "Daftar" di pojok kanan atas, lalu isi formulir pendaftaran.'
            ],
            [
                'question' => 'Bagaimana cara mencari kos?',
                'answer' => 'Gunakan fitur pencarian dan filter berdasarkan lokasi, harga, dan fasilitas.'
            ],
            [
                'question' => 'Bagaimana jika saya lupa password?',
                'answer' => 'Gunakan fitur "Lupa Password" untuk mengatur ulang kata sandi Anda melalui email.'
            ],
            [
                'question' => 'Apakah bisa menghubungi pemilik kos secara langsung?',
                'answer' => 'Ya, setelah login Anda bisa mengirim pesan ke pemilik kos melalui fitur chat.'
            ],
            [
                'question' => 'Apakah data saya aman?',
                'answer' => 'Kami menggunakan sistem keamanan data untuk melindungi informasi pribadi Anda.'
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
