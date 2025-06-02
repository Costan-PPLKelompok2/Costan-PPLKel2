<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EditKosTest extends DuskTestCase
{
    /** @test */
    public function TC_EditKos_001_form_edit_terbuka()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/dashboard')
                    ->clickLink('Edit Kos') // ganti sesuai UI
                    ->assertSee('Edit Kos');
        });
    }

    /** @test */
    public function TC_EditKos_002_edit_dengan_data_valid()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/dashboard')
                    ->clickLink('Edit Kos')
                    ->type('nama_kos', 'Kos Baru Update')
                    ->press('Simpan')
                    ->assertSee('Kos berhasil diperbarui');
        });
    }

    /** @test */
    public function TC_EditKos_003_edit_dengan_field_kosong()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/dashboard')
                    ->clickLink('Edit Kos')
                    ->type('nama_kos', '') // Kosong
                    ->press('Simpan')
                    ->assertSee('field harus diisi'); // sesuaikan pesan validasi
        });
    }
}
