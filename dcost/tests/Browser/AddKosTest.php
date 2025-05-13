<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AddKosTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** TC.AddKos.001 - Menampilkan form tambah kos */
    public function testMenampilkanFormTambahKos()
    {
        $user = User::factory()->create(); // Membuat user baru

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/dashboard')
                    ->waitForText('Tambah Kos', 5) // Tunggu sampai 'Tambah Kos' muncul
                    ->clickLink('Tambah Kos')
                    ->assertPathIs('/kos/create')
                    ->assertSee('Tambah Data Kos')
                    ->assertPresent('input[name=nama_kos]');
        });
    }

    /** TC.AddKos.002 - Tambah kos dengan data valid */
    public function testTambahKosDenganDataValid()
    {
        Storage::fake('public');
        $user = User::factory()->create(); // Membuat user baru

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/kos/create')
                    ->assertPresent('input[name=nama_kos]') // Periksa apakah elemen input ada
                    ->type('nama_kos', 'Kos Mawar')
                    ->type('deskripsi', 'Kos nyaman di pusat kota')
                    ->type('alamat', 'Jl. Mawar No. 10')
                    ->type('harga', '500000')
                    ->check('fasilitas[]', 'wifi') // Sesuaikan dengan fasilitas yang ada
                    ->attach('gambar', __DIR__.'/files/sample.jpg')
                    ->waitFor('button[type="submit"]', 5) // Tunggu tombol Simpan muncul
                    ->press('Simpan')
                    ->assertPathIs('/dashboard')
                    ->assertSee('Kos Mawar');
        });
    }

    /** TC.AddKos.003 - Tambah kos dengan field kosong */
    public function testTambahKosDenganFieldKosong()
    {
        $user = User::factory()->create(); // Membuat user baru

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/kos/create')
                    ->waitFor('button[type="submit"]', 5) // Tunggu tombol Simpan muncul
                    ->press('Simpan')
                    ->assertSee('field harus diisi'); // Sesuaikan dengan pesan validasi
        });
    }

    /** TC.AddKos.004 - Upload gambar kos dengan format valid */
    public function testUploadGambarKosValid()
    {
        Storage::fake('public');
        $user = User::factory()->create(); // Membuat user baru

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/kos/create')
                    ->assertPresent('input[name=nama_kos]') // Periksa apakah elemen input ada
                    ->type('nama_kos', 'Kos Melati')
                    ->type('deskripsi', 'Kos nyaman')
                    ->type('alamat', 'Jl. Melati No. 11')
                    ->type('harga', '600000')
                    ->attach('gambar', __DIR__.'/files/sample.jpg')
                    ->waitFor('button[type="submit"]', 5) // Tunggu tombol Simpan muncul
                    ->press('Simpan')
                    ->assertSee('Kos Melati');
        });
    }

    /** TC.AddKos.005 - Upload gambar dengan format tidak valid */
    public function testUploadGambarKosTidakValid()
    {
        Storage::fake('public');
        $user = User::factory()->create(); // Membuat user baru

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/kos/create')
                    ->assertPresent('input[name=nama_kos]') // Periksa apakah elemen input ada
                    ->type('nama_kos', 'Kos Melati')
                    ->type('deskripsi', 'Kos nyaman')
                    ->type('alamat', 'Jl. Melati No. 11')
                    ->type('harga', '600000')
                    ->attach('gambar', __DIR__.'/files/sample.txt') // File tidak valid
                    ->waitFor('button[type="submit"]', 5) // Tunggu tombol Simpan muncul
                    ->press('Simpan')
                    ->assertSee('format file tidak valid');
        });
    }
}
