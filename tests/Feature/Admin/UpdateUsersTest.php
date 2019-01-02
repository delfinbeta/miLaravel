<?php

namespace Tests\Feature\Admin;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUsersTest extends TestCase
{
  use RefreshDatabase;

  protected $defaultData = [
    'name' => 'Dayan Betancourt',
    'email' => 'dkbetancourt@gmail.com',
    'password' => 'dayan123',
    'profession_id' => '',
    'bio' => 'Programador de Laravel',
    'twitter' => 'https://twitter.com/delfinbeta',
    'role' => 'user'
  ];

  /**
   * A basic test example.
   *
   * @return void
   */
  public function test_it_loads_edit_user() {
    $user = factory(User::class)->create();

    $this->get("/usuarios/{$user->id}/edit")
         ->assertStatus(200)
         ->assertViewIs('users.edit')
         ->assertSee('Editar Usuario')
         ->assertViewHas('user', function ($viewUser) use ($user) {
          return $viewUser->id == $user->id;
         });
  }

  public function test_it_update_user() {
    $user = factory(User::class)->create();

    $this->put("/usuarios/{$user->id}", [
      'name' => 'Dayan Betancourt',
      'email' => 'dkbetancourt@gmail.com',
      'password' => 'dayan123'
    ])->assertRedirect("/usuarios/{$user->id}");

    $this->assertCredentials([
      'name' => 'Dayan Betancourt',
      'email' => 'dkbetancourt@gmail.com',
      'password' => 'dayan123'
    ]);
  }

  public function test_name_required() {
    $this->handleValidationExceptions();

    $user = factory(User::class)->create();

    $this->from("/usuarios/{$user->id}/edit")
         ->put("/usuarios/{$user->id}", [
          'name' => '',
          'email' => 'dkbetancourt@gmail.com',
          'password' => 'dayan123'
         ])->assertRedirect("/usuarios/{$user->id}/edit")
           ->assertSessionHasErrors(['name']);

    $this->assertDatabaseMissing('users', [
      'email' => 'dkbetancourt@gmail.com',
    ]);
  }

  public function test_email_required() {
    $this->handleValidationExceptions();
    
    $user = factory(User::class)->create();

    $this->from("/usuarios/{$user->id}/edit")
         ->put("/usuarios/{$user->id}", [
          'name' => 'Dayan Betancourt',
          'email' => '',
          'password' => 'dayan123'
         ])->assertRedirect("/usuarios/{$user->id}/edit")
           ->assertSessionHasErrors(['email']);

    $this->assertDatabaseMissing('users', [
      'name' => 'Dayan Betancourt',
    ]);
  }

  public function test_email_invalid() {
    $this->handleValidationExceptions();

    $user = factory(User::class)->create();

    $this->from("/usuarios/{$user->id}/edit")
         ->put("/usuarios/{$user->id}", [
          'name' => 'Dayan Betancourt',
          'email' => 'email_no_valido.com',
          'password' => 'dayan123'
         ])->assertRedirect("/usuarios/{$user->id}/edit")
           ->assertSessionHasErrors(['email']);

    $this->assertDatabaseMissing('users', [
      'email' => 'email_no_valido.com',
    ]);
  }

  public function test_email_same() {
    $this->handleValidationExceptions();

    $oldEmail = 'dkbetancourt@gmail.com';

    $user = factory(User::class)->create([
      'email' => $oldEmail
    ]);

    $this->from("/usuarios/{$user->id}/edit")
         ->put("/usuarios/{$user->id}", [
          'name' => 'Dayan Betancourt',
          'email' => $oldEmail,
          'password' => ''
         ])->assertRedirect("/usuarios/{$user->id}");

    $this->assertDatabaseHas('users', [
      'name' => 'Dayan Betancourt',
      'email' => $oldEmail
    ]);
  }

  public function test_email_unique() {
    $this->handleValidationExceptions();

    factory(User::class)->create([
      'email' => 'email-existe@gmail.com'
    ]);

    $user = factory(User::class)->create([
      'email' => 'dkbetancourt@gmail.com'
    ]);

    $this->from("/usuarios/{$user->id}/edit")
         ->put("/usuarios/{$user->id}", [
          'name' => 'Dayan Betancourt',
          'email' => 'email-existe@gmail.com',
          'password' => 'existe123'
         ])->assertRedirect("/usuarios/{$user->id}/edit")
           ->assertSessionHasErrors(['email']);

    // $this->assertEquals(1, User::count());
  }

  public function test_password_optional() {
    $this->handleValidationExceptions();

    $oldPassword = 'CLAVE_ANTERIOR';

    $user = factory(User::class)->create([
      'password' => bcrypt($oldPassword)
    ]);

    $this->from("/usuarios/{$user->id}/edit")
         ->put("/usuarios/{$user->id}", [
          'name' => 'Dayan Betancourt',
          'email' => 'dkbetancourt@gmail.com',
          'password' => ''
         ])->assertRedirect("/usuarios/{$user->id}");

    $this->assertCredentials([
      'name' => 'Dayan Betancourt',
      'email' => 'dkbetancourt@gmail.com',
      'password' => $oldPassword
    ]);
  }
}
