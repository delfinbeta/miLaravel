<?php

namespace Tests\Feature\Admin;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteUsersTest extends TestCase
{
  use RefreshDatabase;

  /**
   * A basic test example.
   *
   * @return void
   */
  public function test_delete_user() {
    $user = factory(User::class)->create();

    $this->delete("/usuarios/{$user->id}")
         ->assertRedirect('usuarios');

    $this->assertDatabaseMissing('users', [
      'id' => $user->id
    ]);
  }
}
