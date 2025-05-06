<?php

namespace Tests\Browser;

use App\Models\Kos;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PopulerTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function test_menampilkan_kos_populer_berdasarkan_pencarian()
    {
        $user = User::factory()->create();
        $kosPopuler = Kos::orderBy('views', 'desc')->take(3)->get();

        $this->browse(function (Browser $browser) use ($user, $kosPopuler) {
            $browser->loginAs($user)
                    ->visit('/dashboard')
                    ->assertSee('Kos Terpopuler');
        });
    }
}
