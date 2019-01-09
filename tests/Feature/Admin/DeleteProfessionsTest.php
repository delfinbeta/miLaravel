<?php

namespace Tests\Feature\Admin;

use App\{User, Profession, UserProfile};
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteProfessionsTest extends TestCase
{
  use RefreshDatabase;

  /**
   * A basic test example.
   *
   * @return void
   */
  public function test_delete_profession() {
    $profession = factory(Profession::class)->create();

    $response = $this->delete("/profesiones/{$profession->id}");

    $response->assertRedirect();

    $this->assertDatabaseEmpty('professions');
  }

  public function test_profession_asociated_profile_cannot_deleted() {
    $this->withExceptionHandling();

    $profession = factory(Profession::class)->create();

    $profile = factory(UserProfile::class)->create([
      'profession_id' => $profession->id,
      'user_id' => factory(User::class)->create()->id
    ]);

    $response = $this->delete("/profesiones/{$profession->id}");

    $response->assertStatus(400);

    $this->assertDatabaseHas('professions', [
      'id' => $profession->id
    ]);
  }
}
