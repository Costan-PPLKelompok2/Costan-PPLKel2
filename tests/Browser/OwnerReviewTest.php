<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class OwnerReviewTest extends DuskTestCase
{
    ///PBI 4

    /**
     * @test
     * @group user-Review-pages
     */
    public function test_user_can_view_owner_review(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertPathIs('/kos')
                ->assertSee("Daftar Kos Saya")
                ->pause(3000)
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertSee("Berikan Review untuk Pemilik Kos 1")
                ->screenshot('test_user_can_review');
        });
    }

    /**
     * @test
     * @group login-Owner-Review-pages
     */
    public function test_user_login_to_view_owner_review(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertPathIs('/kos')
                ->assertSee("Daftar Kos Saya")
                ->pause(3000)
                ->assertSee("Masuk ke Cost'an")
                ->screenshot('test_user_login_to_view_owner_review');
        });
    }

    /**
     * @test
     * @group Create-Owner-Review-pages
     */
    public function test_user_can_create_owner_review(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertPathIs('/kos')
                ->assertSee("Daftar Kos Saya")
                ->pause(3000)
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertSee("Berikan Review untuk Pemilik Kos 1")
                ->pause(3000)
                ->type('comment', 'ownernyaa kurang menata')
                ->press('Kirim Review')
                ->pause(3000)
                ->screenshot('test_user_can_create_owner_review');
        });
    }

    /**
     * @test
     * @group Create-Owner-Review-without-rating
     */
    public function test_user_Create_Owner_Review_without_rating(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertPathIs('/kos')
                ->assertSee("Daftar Kos Saya")
                ->pause(3000)
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertSee("Berikan Review untuk Pemilik Kos 1")
                ->pause(3000)
                ->type('comment', 'ownernyaa baik, tapi galak')
                ->press('Kirim Review')
                ->pause(3000)
                ->screenshot('test_user_Create_Owner_Review_without_rating');
        });
    }

    /**
     * @test
     * @group Create-Owner-Review-without-comment
     */
    public function test_user_Create_Owner_Review_without_comment(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertPathIs('/kos')
                ->assertSee("Daftar Kos Saya")
                ->pause(3000)
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertSee("Berikan Review untuk Pemilik Kos 1")
                ->pause(3000)
                ->press('Kirim Review')
                ->pause(3000)
                ->screenshot('test_user_Create_Owner_Review_without_comment');
        });
    }


    /**
     * @test
     * @group Edit-Owner-Review-pages
     */
    public function test_user_Edit_Owner_Review(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertPathIs('/kos')
                ->assertSee("Daftar Kos Saya")
                ->pause(5000)
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->pause(10000)
                ->type('comment', 'biasaa tapi ga biasa')
                ->press('Update Review')
                ->pause(10000)
                ->screenshot('test_user_Edit_Owner_Review');
        });
    }

    /**
     * @test
     * @group cant-Edit-Owner-Review-pages
     */
    public function test_user_cant_Edit_other_Owner_Review(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertPathIs('/kos')
                ->assertSee("Daftar Kos Saya")
                ->pause(4000)
                ->type('email', 'nal@gmail.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->pause(5000)
                ->screenshot('test_user_cant_Edit_other_Owner_Review');
        });
    }

        /**
     * @test
     * @group Delete-Owner-Review-pages
     */
    public function test_user_Delete_Owner_Review(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertPathIs('/kos')
                ->assertSee("Daftar Kos Saya")
                ->pause(5000)
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertSee("User Profile")
                ->pause(10000)
                ->assertSee("Apakah Anda yakin ingin menghapus review ini?")
                ->press('Hapus Review')
                ->assertsee("Ulasan berhasil dihapus!")
                ->pause(3000)
                ->screenshot('test_user_Delete_Owner_Review');
        });
    }

    /**
     * @test
     * @group cant-Delete-Owner-Review-pages
     */
    public function test_user_cant_Delete_other_Owner_Review(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertPathIs('/kos')
                ->assertSee("Daftar Kos Saya")
                ->pause(5000)
                ->type('email', 'nal@gmail.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->pause(10000)
                ->screenshot('test_user_cant_Delete_other_Owner_Review');
        });
    }

    /**
     * @test
     * @group cant-Create-Owner-Review-twice_pages
     */
    public function test_user_can_create_owner_review_twice(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertPathIs('/kos')
                ->assertSee("Daftar Kos Saya")
                ->pause(3000)
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertSee("Berikan Review untuk Pemilik Kos 1")
                ->pause(3000)
                ->type('comment', 'pemiliknyaa baik hati')
                ->press('Kirim Review')
                ->pause(10000)
                ->screenshot('test_user_can_create_owner_review_twice');
        });
    }


    ///PBI 5
    
    /**
     * @test
     * @group User-Review-detail-pages
     */
    public function test_user_can_view_owner_review_detail(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertPathIs('/kos')
                ->assertSee("Daftar Kos Saya")
                ->pause(3000) 
                ->type('email', 'test@gmail.com')
                ->type('password', '123123123')
                ->press('LOG IN')
                ->assertSee("Pemilik Kos 1")
                ->pause(3000)
                ->screenshot('test_user_can_view_owner_review_detail');
        });
    }

    /**
         * @test
         * @group Owner-Review-list-pages
         */
        public function test_user_can_view_owner_review_list(): void
        {
            $this->browse(function (Browser $browser) {
                $browser->visit('/login')
                    ->type('email', 'pemilik1@example.com')
                    ->type('password', 'password')
                    ->press('LOG IN')
                    ->assertPathIs('/kos')
                    ->assertSee("Daftar Kos Saya")
                    ->pause(3000) 
                    ->type('email', 'pemilik1@example.com')
                    ->type('password', 'password')
                    ->press('LOG IN')
                    ->assertSee("Pemilik Kos 1")
                    ->pause(5000)
                    ->screenshot('test_user_can_view_owner_review_list');
            });
        }

        /**
         * @test
         * @group Owner-Review-total-pages
         */
        public function test_user_can_view_owner_review_total(): void
        {
            $this->browse(function (Browser $browser) {
                $browser->visit('/login')
                    ->type('email', 'pemilik1@example.com')
                    ->type('password', 'password')
                    ->press('LOG IN')
                    ->assertPathIs('/kos')
                    ->assertSee("Daftar Kos Saya")
                    ->pause(3000) 
                    ->type('email', 'pemilik1@example.com')
                    ->type('password', 'password')
                    ->press('LOG IN')
                    ->assertSee("Showing 1 to 5 of 11 results")
                    ->pause(5000)
                    ->screenshot('test_user_can_view_owner_review_total');
            });
        }

    /**
     * @test
     * @group Owner-Review-newest-pages
     */
    public function test_user_can_view_owner_review_newest(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertPathIs('/kos')
                ->assertSee("Daftar Kos Saya")
                ->pause(3000) 
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertSee("Pemilik Kos 1")
                ->press('Terbaru')
                ->pause(5000)
                ->screenshot('test_user_can_view_owner_review_newest');
        });
    }

    /**
     * @test
     * @group Owner-Review-oldest-pages
     */
    public function test_user_can_view_owner_review_oldest(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertPathIs('/kos')
                ->assertSee("Daftar Kos Saya")
                ->pause(3000) 
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertSee("Pemilik Kos 1")
                ->press('Terlama')
                ->pause(5000)
                ->screenshot('test_user_can_view_owner_review_oldest');
        });
    }


    /**
     * @test
     * @group Owner-Review-highrating-pages
     */
    public function test_user_can_view_owner_review_highrating(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertPathIs('/kos')
                ->assertSee("Daftar Kos Saya")
                ->pause(3000) 
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertSee("Pemilik Kos 1")
                ->press('Rating Tertinggi')
                ->pause(5000)
                ->screenshot('test_user_can_view_owner_review_highrating');
        });
    }

    /**
     * @test
     * @group Review-pages-empty
     */
    public function test_user_can_review_empty(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'test@gmail.com')
                ->type('password', '123123123')
                ->press('LOG IN')
                ->pause(2000)
                ->assertSee("Cari kos idaman anda sekarang!")
                ->pause(5000)
                ->type('email', 'test@gmail.com')
                ->type('password', '123123123')
                ->press('LOG IN')
                ->assertSee("Review Kos")
                ->screenshot('test_user_can_review_empty');
        });
    }

    /**
     * @test
     * @group Owner-Review-pages-empty
     */
    public function test_user_can_view_owner_review_empty(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertSee("Daftar Kos Saya")
                ->pause(3000)
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertSee("Belum ada review")
                ->screenshot('test_user_can_review_empty');
        });
    }

     /**
     * @test
     * @group Owner-Review-klik
     */
    public function test_user_can_klik_owner_review(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertPathIs('/kos')
                ->assertSee("Daftar Kos Saya")
                ->pause(3000)
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertSee("Pemilik Kos 1")
                ->screenshot('test_user_can_klik_owner_review');
        });
    }

    /**
     * @test
     * @group Owner-Review-filter
     */
    public function test_user_can_filter_owner_review(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertPathIs('/kos')
                ->assertSee("Daftar Kos Saya")
                ->pause(3000)
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertSee("Pemilik Kos 1")
                ->press('Filter Rating')
                ->pause(5000) //klik 5 Bintang
                ->screenshot('test_user_can_filter_owner_review');
        });
    }

    /**
     * @test
     * @group Owner-Review-pages
     */
    public function test_owner_can_view_owner_review(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertPathIs('/kos')
                ->assertSee("Daftar Kos Saya")
                ->pause(3000)
                ->type('email', 'pemilik1@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertSee("Pemilik Kos 1")
                ->screenshot('test_owner_can_view_owner_review');
        });
    }
}