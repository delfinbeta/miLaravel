<?php

namespace Tests\Feature\Admin;

use App\Profession;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListProfessionsTest extends TestCase
{
	use RefreshDatabase;

  /**
   * A basic test example.
   *
   * @return void
   */
  public function test_shows_professions_list()
  {
    factory(Profession::class)->create(['title' => 'Diseñador']);
    factory(Profession::class)->create(['title' => 'Programador']);
    factory(Profession::class)->create(['title' => 'Administrador']);

    $this->get('/profesiones')
    		 ->assertStatus(200)
    		 ->assertSeeInOrder([
    		 	'Administrador',
    		 	'Diseñador',
    		 	'Programador'
    		 ]);
  }
}
