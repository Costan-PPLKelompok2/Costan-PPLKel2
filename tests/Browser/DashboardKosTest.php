<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DashboardKosTest extends DuskTestCase
{
    /** @test */
    public function TC_DashKos_001_kos_ditampilkan()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/dashboard')
                    ->assertSee('Nama Kos'); // ubah sesuai konten dashboard
        });
    }

    /** @test */
    public function TC_DashKos_002_dashboard_kos_kosong()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/dashboard')
                    ->assertSee('Belum ada kos yang ditambahkan');
        });
    }

    /** @test */
    public function TC_DashKos_003_tidak_tampil_untuk_user_lain()
    {
        $user = User::factory()->create();
        $userLain = User::factory()->create();

        $this->browse(function (Browser $browser) use ($userLain) {
            $browser->loginAs($userLain)
                    ->visit('/dashboard')
                    ->assertSee('Belum ada kos terdaftar');
        });
    }

    /** @test */
    public function TC_DashKos_004_data_kos_lengkap()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/dashboard')
                    ->assertSee('Harga')
                    ->assertSee('Alamat')
                    ->assertSee('Fasilitas');
        });
    }

    /** @test */
    public function TC_DashKos_005_tampilan_struktur_tabel()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/dashboard')
                    ->assertVisible('table') // Asumsikan tabel daftar kos
                    ->assertSee('Nama Kos');
        });
    }
}
