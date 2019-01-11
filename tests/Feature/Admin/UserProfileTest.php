<?php

namespace Tests\Feature\Admin;

use App\{User, UserProfile, Profession};
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserProfileTest extends TestCase
{
	use RefreshDatabase;

	protected $defaultData = [
    'first_name' => 'Dayan',
    'last_name' => 'Betancourt',
    'email' => 'dkbetancourt@gmail.com',
    'bio' => 'Programador de Laravel',
    'twitter' => 'https://twitter.com/delfinbeta',
  ];

  /**
   * A basic test example.
   *
   * @return void
   */
  public function test_user_edit_its_profile()
  {
    $user = factory(User::class)->create();

    $newProfession = factory(Profession::class)->create();

    //$this->actingAs($user);

    $response = $this->get('/perfil/');
    $response->assertStatus(200);

    $response = $this->put('/perfil/', [
    	'first_name' => 'Dayan',
      'last_name' => 'Betancourt',
	    'email' => 'dkbetancourt@gmail.com',
	    'profession_id' => $newProfession->id,
	    'bio' => 'Programador de Laravel',
	    'twitter' => 'https://twitter.com/delfinbeta',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('users', [
      'first_name' => 'Dayan',
      'last_name' => 'Betancourt',
	    'email' => 'dkbetancourt@gmail.com'
    ]);

    $this->assertDatabaseHas('user_profiles', [
      'profession_id' => $newProfession->id,
      'bio' => 'Programador de Laravel',
	    'twitter' => 'https://twitter.com/delfinbeta',
    ]);
  }

  public function test_user_cannot_change_its_role()
  {
    $user = factory(User::class)->create([
      'role' => 'user'
    ]);

    $response = $this->put('/perfil/', $this->getValidData([
      'role' => 'admin',
    ]));

    $response->assertRedirect();

    $this->assertDatabaseHas('users', [
      'id' => $user->id,
      'role' => 'user',
    ]);
  }

  public function test_user_cannot_change_its_password()
  {
    factory(User::class)->create([
      'password' => bcrypt('old123'),
    ]);

    $response = $this->put('/perfil/', $this->getValidData([
      'email' => 'dkbetancourt@gmail.com',
      'password' => 'new456'
    ]));

    $response->assertRedirect();
    
    $this->assertCredentials([
      'email' => 'dkbetancourt@gmail.com',
      'password' => 'old123',
    ]);
  }
}
