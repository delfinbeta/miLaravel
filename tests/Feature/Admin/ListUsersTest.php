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

    $this->assertNotRepeatedQueries();
  }

  public function test_it_loads_users_paginates() {
    factory(User::class)->create([
      'name' => 'Usuario 01',
      'created_at' => now()->subWeek()
    ]);

    factory(User::class)->create([
      'name' => 'Usuario 02',
      'created_at' => now()->subDays(6)
    ]);

    factory(User::class)->create([
      'name' => 'Usuario 17',
      'created_at' => now()->subDays(2)
    ]);

    factory(User::class)->times(12)->create([
      'created_at' => now()->subDays(4)
    ]);

    factory(User::class)->create([
      'name' => 'Usuario 03',
      'created_at' => now()->subDays(5)
    ]);

    factory(User::class)->create([
      'name' => 'Usuario 16',
      'created_at' => now()->subDays(3)
    ]);

    $this->get('/usuarios')
         ->assertStatus(200)
         ->assertSeeInOrder([
          'Usuario 17',
          'Usuario 16',
          'Usuario 03'
         ])
         ->assertDontSee('Usuario 02')
         ->assertDontSee('Usuario 01');

    $this->get('/usuarios?page=2')
         ->assertStatus(200)
         ->assertSeeInOrder([
          'Usuario 02',
          'Usuario 01'
         ])
         ->assertDontSee('Usuario 3');
  }

  public function test_it_loads_users_list_empty() {
    $this->get('/usuarios')
         ->assertStatus(200)
         ->assertSee('No hay usuarios registrados');
  }

  public function test_it_loads_deleted_users_list() {
    factory(User::class)->create([
      'name' => 'Zoraida',
      'deleted_at' => now()
    ]);

    factory(User::class)->create([
      'name' => 'Dayan',
    ]);

    $this->get('/usuarios/papelera')
         ->assertStatus(200)
         ->assertSee('Listado de Usuarios en la Papelera')
         ->assertSee('Zoraida')
         ->assertDontSee('Dayan');
  }
}
