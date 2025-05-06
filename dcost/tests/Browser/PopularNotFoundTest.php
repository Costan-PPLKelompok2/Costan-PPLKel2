<?php

namespace Tests\Browser;

use App\Models\Kos;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PopularNotFoundTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     * @group populer-negative
     */
    public function test_kos_populer_tidak_muncul_ketika_tidak_ada_data()
    {
        $user = User::factory()->create();
        Kos::query()->delete();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/dashboard')
                    ->assertSee('Kos Terpopuler')
                    ->assertSee('Belum ada kos populer yang dapat ditampilkan');

        });
    }
}
