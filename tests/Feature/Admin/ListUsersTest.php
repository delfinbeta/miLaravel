<?php

namespace Tests\Feature\Admin;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListUsersTest extends TestCase
{
	use RefreshDatabase;

  /**
   * A basic test example.
   *
   * @return void
   */
  public function test_it_loads_users_list() {
    factory(User::class)->create([
      'name' => 'Zoraida',
    ]);

    factory(User::class)->create([
      'name' => 'Dayan',
    ]);

    $this->get('/usuarios')
         ->assertStatus(200)
         ->assertSee('Usuarios')
         ->assertSee('Zoraida')
         ->assertSee('Dayan');
  }

  public function test_it_loads_users_list_empty() {
    $this->get('/usuarios')
         ->assertStatus(200)
         ->assertSee('No hay usuarios registrados');
  }
}
