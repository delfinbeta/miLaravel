<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WelcomeUsersTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_welcomes_users_with_nickname()
    {
        $this->get('/saludo/dayan/delfinbeta')
        		 ->assertStatus(200)
        		 ->assertSee('Bienvenido Dayan, tu apodo es delfinbeta');
    }

    public function test_it_welcomes_users_without_nickname()
    {
        $this->get('/saludo/dayan')
        		 ->assertStatus(200)
        		 ->assertSee('Bienvenido Dayan');
    }
}
