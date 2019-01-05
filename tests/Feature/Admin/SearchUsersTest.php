<?php

namespace Tests\Feature\Admin;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchUsersTest extends TestCase
{
  use RefreshDatabase;

  /**
   * A basic test example.
   *
   * @return void
   */
  public function test_search_users_name() {
    $usuario1 = factory(User::class)->create([
      'name' => 'Dayan',
    ]);

    $usuario2 = factory(User::class)->create([
      'name' => 'Zoraida',
    ]);

    $this->get('/usuarios?search=Dayan')
         ->assertStatus(200)
         ->assertViewHas('users', function($users) use ($usuario1, $usuario2) {
         	return $users->contains($usuario1) && !$users->contains($usuario2);
         });
  }

  public function test_search_partial_users_name() {
    $usuario1 = factory(User::class)->create([
      'name' => 'Dayan',
    ]);

    $usuario2 = factory(User::class)->create([
      'name' => 'Zoraida',
    ]);

    $this->get('/usuarios?search=Day')
         ->assertStatus(200)
         ->assertViewHas('users', function($users) use ($usuario1, $usuario2) {
         	return $users->contains($usuario1) && !$users->contains($usuario2);
         });
  }

  public function test_search_users_email() {
    $usuario1 = factory(User::class)->create([
      'email' => 'dkbetancourt@gmail.com',
    ]);

    $usuario2 = factory(User::class)->create([
      'email' => 'otro@email.com',
    ]);

    $this->get('/usuarios?search=dkbetancourt@gmail.com')
         ->assertStatus(200)
         ->assertViewHas('users', function($users) use ($usuario1, $usuario2) {
         	return $users->contains($usuario1) && !$users->contains($usuario2);
         });
  }

  public function test_search_partial_users_email() {
    $usuario1 = factory(User::class)->create([
      'email' => 'dkbetancourt@gmail.com',
    ]);

    $usuario2 = factory(User::class)->create([
      'email' => 'otro@email.com',
    ]);

    $this->get('/usuarios?search=@gmail.com')
         ->assertStatus(200)
         ->assertViewHas('users', function($users) use ($usuario1, $usuario2) {
         	return $users->contains($usuario1) && !$users->contains($usuario2);
         });
  }
}
