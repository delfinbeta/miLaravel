<?php

namespace Tests\Feature\Admin;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowUsersTest extends TestCase
{
  use RefreshDatabase;

  /**
   * A basic test example.
   *
   * @return void
   */
  public function test_it_displays_users_details() {
    $user = factory(User::class)->create([
      'first_name' => 'Dayan',
      'last_name' => 'Betancourt',
    ]);

    $this->get("/usuarios/{$user->id}")
         ->assertStatus(200)
         ->assertSee('Dayan Betancourt');
  }

  public function test_404_error_user_not_found() {
    $this->withExceptionHandling();

    $this->get('/usuarios/999')
         ->assertStatus(404)
         ->assertSee('Página no encontrada');
  }
}
