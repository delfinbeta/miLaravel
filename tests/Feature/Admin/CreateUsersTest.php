<?php

namespace Tests\Feature\Admin;

use App\{User, Profession, Skill};
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateUsersTest extends TestCase
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
  public function test_it_loads_new_user() {
    $profession = factory(Profession::class)->create();

    $skillA = factory(Skill::class)->create();
    $skillB = factory(Skill::class)->create();

    $this->get('/usuarios/nuevo')
         ->assertStatus(200)
         ->assertSee('Crear Nuevo Usuario')
         ->assertViewHas('professions', function($professions) use($profession) {
          return $professions->contains($profession);
         })
         ->assertViewHas('skills', function($skills) use($skillA, $skillB) {
          return $skills->contains($skillA) && $skills->contains($skillB);
         });
  }

  public function test_it_creates_new_user() {
    // $this->withoutExceptionHandling();

    // $this->post('/usuarios/nuevo', [
    //   'name' => 'Dayan Betancourt',
    //   'email' => 'dkbetancourt@gmail.com',
    //   'password' => 'dayan123',
    //   'bio' => 'Programador de Laravel',
    //   'twitter' => 'https://twitter.com/delfinbeta'
    // ])->assertRedirect('usuarios');

    $profession = factory(Profession::class)->create();

    $skillA = factory(Skill::class)->create();
    $skillB = factory(Skill::class)->create();
    $skillC = factory(Skill::class)->create();

    $this->post('/usuarios/nuevo', $this->getValidData([
      'skills' => [$skillA->id, $skillB->id],
      'profession_id' => $profession->id
    ]))->assertRedirect('usuarios');

    // $this->assertDatabaseHas('users', [
    //   'name' => 'Dayan Betancourt',
    //   'email' => 'dkbetancourt@gmail.com',
    // ]);

    $this->assertCredentials([
      'name' => 'Dayan Betancourt',
      'email' => 'dkbetancourt@gmail.com',
      'password' => 'dayan123',
      'role' => 'user'
    ]);

    $user = User::findByEmail('dkbetancourt@gmail.com');

    $this->assertDatabaseHas('user_profiles', [
      'bio' => 'Programador de Laravel',
      'twitter' => 'https://twitter.com/delfinbeta',
      'profession_id' => $profession->id,
      'user_id' => $user->id
    ]);

    $this->assertDatabaseHas('user_skill', [
      'user_id' => $user->id,
      'skill_id' => $skillA->id
    ]);

    $this->assertDatabaseHas('user_skill', [
      'user_id' => $user->id,
      'skill_id' => $skillB->id
    ]);

    $this->assertDatabaseMissing('user_skill', [
      'user_id' => $user->id,
      'skill_id' => $skillC->id
    ]);
  }

  public function test_name_required() {
    $this->handleValidationExceptions();

    $this->from('/usuarios/nuevo')
         ->post('/usuarios/nuevo', $this->getValidData([
          'name' => ''
         ]))->assertRedirect('/usuarios/nuevo')
           ->assertSessionHasErrors(['name' => 'El campo nombre es obligatorio']);

    // $this->assertEquals(0, User::count());

    $this->assertDatabaseMissing('users', [
      'email' => 'dkbetancourt@gmail.com',
    ]);
  }

  public function test_email_required() {
    $this->handleValidationExceptions();

    $this->from('/usuarios/nuevo')
         ->post('/usuarios/nuevo', $this->getValidData([
          'email' => ''
         ]))->assertRedirect('/usuarios/nuevo')
           ->assertSessionHasErrors(['email' => 'El campo email es obligatorio']);

    $this->assertDatabaseMissing('users', [
      'email' => 'dkbetancourt@gmail.com',
    ]);
  }

  public function test_email_invalid() {
    $this->handleValidationExceptions();

    $this->from('/usuarios/nuevo')
         ->post('/usuarios/nuevo', $this->getValidData([
          'email' => 'email_no_valido.com'
         ]))->assertRedirect('/usuarios/nuevo')
           ->assertSessionHasErrors(['email' => 'Email inválido']);

    $this->assertDatabaseMissing('users', [
      'email' => 'dkbetancourt@gmail.com',
    ]);
  }

  public function test_email_unique() {
    $this->handleValidationExceptions();

    factory(User::class)->create([
      'email' => 'dkbetancourt@gmail.com'
    ]);

    $this->from('/usuarios/nuevo')
         ->post('/usuarios/nuevo', $this->getValidData([
          'email' => 'dkbetancourt@gmail.com'
         ]))->assertRedirect('/usuarios/nuevo')
           ->assertSessionHasErrors(['email' => 'Email ya registrado']);

    $this->assertEquals(1, User::count());
  }

  public function test_password_required() {
    $this->handleValidationExceptions();

    $this->from('/usuarios/nuevo')
         ->post('/usuarios/nuevo', $this->getValidData([
          'password' => ''
         ]))->assertRedirect('/usuarios/nuevo')
           ->assertSessionHasErrors(['password' => 'El campo contraseña es obligatorio']);

    $this->assertDatabaseMissing('users', [
      'email' => 'dkbetancourt@gmail.com',
    ]);
  }

  public function test_password_6caracteres() {
    $this->handleValidationExceptions();

    $this->from('/usuarios/nuevo')
         ->post('/usuarios/nuevo', $this->getValidData([
          'password' => '123'
         ]))->assertRedirect('/usuarios/nuevo')
           ->assertSessionHasErrors(['password' => 'La contraseña debe contener mínimo 6 caracteres']);

    $this->assertDatabaseMissing('users', [
      'email' => 'dkbetancourt@gmail.com',
    ]);
  }

  public function test_role_optional() {
    $this->handleValidationExceptions();

    $this->post('/usuarios/nuevo', $this->getValidData([
      'role' => null
    ]))->assertRedirect('usuarios');

    $this->assertDatabaseHas('users', [
      'email' => 'dkbetancourt@gmail.com',
      'role' => 'user'
    ]);
  }

  public function test_role_invalid() {
    $this->handleValidationExceptions();

    $this->from('/usuarios/nuevo')
         ->post('/usuarios/nuevo', $this->getValidData([
      'role' => 'invalid-role'
    ]))->assertRedirect('/usuarios/nuevo')
       ->assertSessionHasErrors(['role']);

    $this->assertDatabaseMissing('users', [
      'email' => 'dkbetancourt@gmail.com',
    ]);
  }

  public function test_profession_id_optional() {
    $this->from('/usuarios/nuevo')
         ->post('/usuarios/nuevo', $this->getValidData([
      'profession_id' => null
    ]))->assertRedirect('usuarios');

    $this->assertCredentials([
      'name' => 'Dayan Betancourt',
      'email' => 'dkbetancourt@gmail.com',
      'password' => 'dayan123',
    ]);

    $this->assertDatabaseHas('user_profiles', [
      'bio' => 'Programador de Laravel',
      'profession_id' => null,
      'user_id' => User::findByEmail('dkbetancourt@gmail.com')->id
    ]);
  }

  public function test_profession_invalid() {
    $this->handleValidationExceptions();

    $this->from('/usuarios/nuevo')
         ->post('/usuarios/nuevo', $this->getValidData([
      'profession_id' => '999'
    ]))->assertRedirect('usuarios/nuevo')
       ->assertSessionHasErrors(['profession_id']);

    $this->assertDatabaseMissing('users', [
      'email' => 'dkbetancourt@gmail.com',
    ]);
  }

  public function test_only_profession_no_deleted() {
    $this->handleValidationExceptions();

    $deletedProfession = factory(Profession::class)->create([
      'deleted_at' => now()->format('Y-m-d')
    ]);

    $this->from('/usuarios/nuevo')
         ->post('/usuarios/nuevo', $this->getValidData([
      'profession_id' => $deletedProfession->id
    ]))->assertRedirect('usuarios/nuevo')
       ->assertSessionHasErrors(['profession_id']);

    $this->assertDatabaseMissing('users', [
      'email' => 'dkbetancourt@gmail.com',
    ]);
  }

  public function test_twitter_optional() {
    $this->handleValidationExceptions();

    $this->post('/usuarios/nuevo', $this->getValidData([
      'twitter' => null
    ]))->assertRedirect('usuarios');

    $this->assertCredentials([
      'name' => 'Dayan Betancourt',
      'email' => 'dkbetancourt@gmail.com',
      'password' => 'dayan123'
    ]);

    $this->assertDatabaseHas('user_profiles', [
      'bio' => 'Programador de Laravel',
      'twitter' => null,
      'user_id' => User::findByEmail('dkbetancourt@gmail.com')->id
    ]);
  }

  public function test_skills_array() {
    $this->handleValidationExceptions();

    $this->from('/usuarios/nuevo')
         ->post('/usuarios/nuevo', $this->getValidData([
      'skills' => 'PHP, JS'
    ]))->assertRedirect('usuarios/nuevo')
       ->assertSessionHasErrors(['skills']);

    $this->assertDatabaseMissing('users', [
      'email' => 'dkbetancourt@gmail.com',
    ]);
  }

  public function test_skills_invalid() {
    $this->handleValidationExceptions();
    
    $skillA = factory(Skill::class)->create();
    $skillB = factory(Skill::class)->create();

    $this->from('/usuarios/nuevo')
         ->post('/usuarios/nuevo', $this->getValidData([
      'skills' => [$skillA->id, $skillB->id + 1]
    ]))->assertRedirect('usuarios/nuevo')
       ->assertSessionHasErrors(['skills']);

    $this->assertDatabaseMissing('users', [
      'email' => 'dkbetancourt@gmail.com',
    ]);
  }
}
