<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{


    /**
     * A Dusk test example.
     */
    public function testExample(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/flota')
                    ->assertSee('flota')
                    ->type('email','brandonmejia887@gmail.com')
                    ->type('password', '4dm1nS3m')
                    ->press('Ingresar')
                    ->assertPathIs('/flota');
        });
    }
}
