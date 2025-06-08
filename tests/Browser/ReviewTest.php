<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ReviewTest extends DuskTestCase
{
    /**
     * @test
     * @group Review-pages
     */
    public function test_user_can_review(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'test@gmail.com')
                ->type('password', '123123123')
                ->press('LOG IN')
                ->assertPathIs('/home')
                ->pause(2000)
                ->assertSee("Cari kos idaman anda sekarang!")
                ->pause(30000)
                ->assertSee("Review Kos")
                ->screenshot('test_user_can_review');
        });
    }

    /**
     * @test
     * @group Review-login-pages
     */
    public function test_user_login_to_review(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'test@gmail.com')
                ->type('password', '123123123')
                ->press('LOG IN')
                ->assertPathIs('/home')
                ->pause(2000)
                ->assertSee("Cari kos idaman anda sekarang!")
                ->pause(20000)
                ->assertSee("Masuk ke Cost'an")
                ->screenshot('test_user_login_to_review');
        });
    }
  
    /**
     * @test
     * @group Review-Create-pages
     */
    public function test_user_can_create_review(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'test@gmail.com')
                ->type('password', '123123123')
                ->press('LOG IN')
                ->assertPathIs('/home')
                ->assertSee("Cari kos idaman anda sekarang!")
                ->pause(20000)
                ->assertSee("Review Kos")
                ->pause(2000)
                ->type('comment', 'nyaman banget kost nya, pengghuninya juga ramah.')
                ->press('Kirim Review')
                ->pause(8000)
                ->screenshot('test_user_can_create_review');
        });
    }

      /**
     * @test
     * @group strange-cant-edit-pages
     */
    public function test_user_cant_edit_other_review(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'test@gmail.com')
                ->type('password', '123123123')
                ->press('LOG IN')
                ->assertPathIs('/home')
                ->assertSee("Cari kos idaman anda sekarang!")
                ->pause(5000)
                ->type('email', 'fawwazreynal1@gmail.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertSee("Review Kos")
                ->pause(5000)
                ->screenshot('test_user_cant_edit_other_review');
        });
    }

    /**
     * @test
     * @group Review-edit-pages
     */
    public function test_user_can_edit_review(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'test@gmail.com')
                ->type('password', '123123123')
                ->press('LOG IN')
                ->assertPathIs('/home')
                ->assertSee("Cari kos idaman anda sekarang!")
                ->pause(5000)
                ->type('email', 'test@gmail.com')
                ->type('password', '123123123')
                ->press('LOG IN')
                ->assertSee("Review Kos")
                ->pause(5000) //klik edit
                ->type('comment', 'halooooooooooooooooo 2025 key')
                ->press('Simpan Perubahan')
                ->pause(5000)
                ->screenshot('test_user_can_edit_review');
        });
    }



          /**
     * @test
     * @group Review-delete-pages
     */
    public function test_user_can_delete_review(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'test@gmail.com')
                ->type('password', '123123123')
                ->press('LOG IN')
                ->assertPathIs('/home')
                ->assertSee("Cari kos idaman anda sekarang!")
                ->pause(5000)
                ->type('email', 'test@gmail.com')
                ->type('password', '123123123')
                ->press('LOG IN')
                ->assertSee("Review Kos")
                ->press('Hapus')
                ->pause(5000)
                ->screenshot('test_user_can_delete_review');
        });
    }

          /**
     * @test
     * @group strange-cant-delete-pages
     */
    public function test_user_cant_delete_other_review(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'test@gmail.com')
                ->type('password', '123123123')
                ->press('LOG IN')
                ->assertPathIs('/home')
                ->assertSee("Cari kos idaman anda sekarang!")
                ->pause(5000)
                ->type('email', 'fawwazreynal1@gmail.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertSee("Review Kos")
                ->pause(5000)
                ->screenshot('test_user_cant_delete_other_review');
        });
    }

        /**
     * @test
     * @group cant-Create-twice-pages
     */
    public function test_user_cant_create_review_twice(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'test@gmail.com')
                ->type('password', '123123123')
                ->press('LOG IN')
                ->assertPathIs('/home')
                ->assertSee("Cari kos idaman anda sekarang!")
                ->pause(20000)
                ->assertSee("Review Kos")
                ->pause(2000)
                ->type('comment', 'nyaman banget kost nya, pengghuninya juga ramah.')
                ->press('Kirim Review')
                ->pause(8000)
                ->screenshot('test_user_cant_create_review_twice');
        });
    }


        /**
     * @test
     * @group view-Review-pages
     */
    public function test_user_can_view_review(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'fawwazreynal1@gmail.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertPathIs('/home')
                ->pause(2000)
                ->assertSee("Cari kos idaman anda sekarang!")
                ->pause(30000)
                ->assertSee("Review Kos")
                ->screenshot('test_user_can_view_review');
        });
    }

     /**
     * @test
     * @group owner-Review-pages
     */
    public function test_owner_can_review(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'test@gmail.com')
                ->type('password', '123123123')
                ->press('LOG IN')
                ->assertPathIs('/home')
                ->pause(3000)
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertSee("Review Kos")
                ->screenshot('test_owner_can_review');
        });
    }


}
