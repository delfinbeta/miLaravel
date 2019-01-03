<?php

namespace Tests\Feature\Admin;

use App\{User, Profession, Skill, UserProfile};
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

    $oldProfession = factory(Profession::class)->create();
    $oldSkill1 = factory(Skill::class)->create();
    $oldSkill2 = factory(Skill::class)->create();

    // $profile = factory(UserProfile::class)->create([
    //   'user_id' => $user->id,
    //   'profession_id' => $oldProfession->id
    // ]);
    $user->profile()->save(factory(UserProfile::class)->make([
      'profession_id' => $oldProfession->id
    ]));

    $user->skills()->attach([$oldSkill1->id, $oldSkill2->id]);

    $newProfession = factory(Profession::class)->create();
    $newSkill1 = factory(Skill::class)->create();
    $newSkill2 = factory(Skill::class)->create();

    $this->put("/usuarios/{$user->id}", [
      'name' => 'Dayan Betancourt',
      'email' => 'dkbetancourt@gmail.com',
      'password' => 'dayan123',
      'profession_id' => $newProfession->id,
      'bio' => 'Programador de Laravel',
      'twitter' => 'https://twitter.com/delfinbeta',
      'role' => 'admin',
      'skills' => [$newSkill1->id, $newSkill2->id]
    ])->assertRedirect("/usuarios/{$user->id}");

    $this->assertCredentials([
      'name' => 'Dayan Betancourt',
      'email' => 'dkbetancourt@gmail.com',
      'password' => 'dayan123',
      'role' => 'admin',
    ]);

    $this->assertDatabaseHas('user_profiles', [
      'user_id' => $user->id,
      'profession_id' => $newProfession->id,
      'bio' => 'Programador de Laravel',
      'twitter' => 'https://twitter.com/delfinbeta',
    ]);

    $this->assertDatabaseCount('user_skill', 2);

    $this->assertDatabaseHas('user_skill', [
      'user_id' => $user->id,
      'skill_id' => $newSkill1->id,
    ]);

    $this->assertDatabaseHas('user_skill', [
      'user_id' => $user->id,
      'skill_id' => $newSkill2->id,
    ]);
  }

  public function test_skills_no_checked() {
    $user = factory(User::class)->create();

    $oldSkill1 = factory(Skill::class)->create();
    $oldSkill2 = factory(Skill::class)->create();

    $user->skills()->attach([$oldSkill1->id, $oldSkill2->id]);

    $this->put("/usuarios/{$user->id}", $this->getValidData())
         ->assertRedirect("/usuarios/{$user->id}");

    $this->assertDatabaseEmpty('user_skill');
  }

  public function test_name_required() {
    $this->handleValidationExceptions();

    $user = factory(User::class)->create();

    $this->from("/usuarios/{$user->id}/edit")
         ->put("/usuarios/{$user->id}", $this->getValidData([
          'name' => ''
         ]))
         ->assertRedirect("/usuarios/{$user->id}/edit")
         ->assertSessionHasErrors(['name']);

    $this->assertDatabaseMissing('users', [
      'email' => 'dkbetancourt@gmail.com',
    ]);
  }

  public function test_email_required() {
    $this->handleValidationExceptions();
    
    $user = factory(User::class)->create();

    $this->from("/usuarios/{$user->id}/edit")
         ->put("/usuarios/{$user->id}", $this->getValidData([
          'email' => ''
         ]))
         ->assertRedirect("/usuarios/{$user->id}/edit")
         ->assertSessionHasErrors(['email']);

    $this->assertDatabaseMissing('users', [
      'name' => 'Dayan Betancourt',
    ]);
  }

  public function test_email_invalid() {
    $this->handleValidationExceptions();

    $user = factory(User::class)->create();

    $this->from("/usuarios/{$user->id}/edit")
         ->put("/usuarios/{$user->id}", $this->getValidData([
          'email' => 'email_no_valido.com'
         ]))
         ->assertRedirect("/usuarios/{$user->id}/edit")
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
         ->put("/usuarios/{$user->id}", $this->getValidData([
          'name' => 'Dayan Betancourt',
          'email' => $oldEmail,
         ]))
         ->assertRedirect("/usuarios/{$user->id}");

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
         ->put("/usuarios/{$user->id}", $this->getValidData([
          'email' => 'email-existe@gmail.com'
         ]))
         ->assertRedirect("/usuarios/{$user->id}/edit")
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
         ->put("/usuarios/{$user->id}", $this->getValidData([
          'password' => ''
         ]))
         ->assertRedirect("/usuarios/{$user->id}");

    $this->assertCredentials([
      'name' => 'Dayan Betancourt',
      'email' => 'dkbetancourt@gmail.com',
      'password' => $oldPassword
    ]);
  }
}
