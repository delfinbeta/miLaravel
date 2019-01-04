<?php

namespace Tests\Feature\Admin;

use App\Skill;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListSkillsTest extends TestCase
{
  use RefreshDatabase;

  /**
   * A basic test example.
   *
   * @return void
   */
  public function test_shows_skills_list()
  {
    factory(Skill::class)->create(['name' => 'PHP']);
    factory(Skill::class)->create(['name' => 'JS']);
    factory(Skill::class)->create(['name' => 'SQL']);

    $this->get('/habilidades')
    		 ->assertStatus(200)
    		 ->assertSeeInOrder([
    		 	'JS',
    		 	'PHP',
    		 	'SQL'
    		 ]);
  }
}
