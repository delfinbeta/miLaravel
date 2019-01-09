<?php

namespace Tests\Feature\Admin;

use App\{User, UserProfile};
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
  public function test_sends_user_to_trash() {
    $user = factory(User::class)->create();
    
    // factory(UserProfile::class)->create([
    //   'user_id' => $user->id
    // ]);

    $this->patch("/usuarios/{$user->id}/papelera")
         ->assertRedirect('usuarios');

    // Option 1:
    $this->assertSoftDeleted('users', [
      'id' => $user->id
    ]);

    $this->assertSoftDeleted('user_profiles', [
      'user_id' => $user->id
    ]);

    // Option 2:
    $user->refresh();
    $this->assertTrue($user->trashed());
  }

  public function test_delete_user() {
    $user = factory(User::class)->create([
      'deleted_at' => now()
    ]);

    // factory(UserProfile::class)->create([
    //   'user_id' => $user->id
    // ]);

    $this->delete("/usuarios/{$user->id}")
         ->assertRedirect('usuarios/papelera');

    $this->assertDatabaseEmpty('users');
  }

  public function test_cannot_delete_user_not_trashed() {
    $this->withExceptionHandling();

    $user = factory(User::class)->create([
      'deleted_at' => null
    ]);

    // factory(UserProfile::class)->create([
    //   'user_id' => $user->id
    // ]);

    $this->delete("/usuarios/{$user->id}")
         ->assertStatus(404);

    $this->assertDatabaseHas('users', [
      'id' => $user->id,
      'deleted_at' => null
    ]);
  }
}
