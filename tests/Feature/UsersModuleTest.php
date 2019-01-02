<?php

namespace Tests\Feature;

use App\User;
use App\Profession;
use App\Skill;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class UsersModuleTest extends TestCase
{
  use RefreshDatabase;

  protected $profession;

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
  }

  public function test_it_loads_users_list_empty() {
    $this->get('/usuarios')
         ->assertStatus(200)
         ->assertSee('No hay usuarios registrados');
  }

  public function test_it_displays_users_details() {
    $user = factory(User::class)->create([
      'name' => 'Dayan Betancourt',
    ]);

    $this->get("/usuarios/{$user->id}")
         ->assertStatus(200)
         ->assertSee('Dayan Betancourt');
  }

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

    $skillA = factory(Skill::class)->create();
    $skillB = factory(Skill::class)->create();
    $skillC = factory(Skill::class)->create();

    $this->post('/usuarios/nuevo', $this->getValidData([
      'skills' => [$skillA->id, $skillB->id]
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
      'profession_id' => $this->profession->id,
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
    $this->post('/usuarios/nuevo', $this->getValidData([
      'role' => null
    ]))->assertRedirect('usuarios');

    $this->assertDatabaseHas('users', [
      'email' => 'dkbetancourt@gmail.com',
      'role' => 'user'
    ]);
  }

  public function test_role_invalid() {
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

  public function test_name_required_updating() {
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

  public function test_email_required_updating() {
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

  public function test_email_invalid_updating() {
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

  public function test_email_same_updating() {
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

  public function test_email_unique_updating() {
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

  public function test_password_optional_updating() {
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

  public function test_delete_user() {
    $user = factory(User::class)->create();

    $this->delete("/usuarios/{$user->id}")
         ->assertRedirect('usuarios');

    $this->assertDatabaseMissing('users', [
      'id' => $user->id
    ]);
  }

  public function test_404_error_user_not_found() {
    $this->get('/usuarios/999')
         ->assertStatus(404)
         ->assertSee('Página no encontrada');
  }

  protected function getValidData(array $custom = []) {
    $this->profession = factory(Profession::class)->create();

    return array_merge([
      'name' => 'Dayan Betancourt',
      'email' => 'dkbetancourt@gmail.com',
      'password' => 'dayan123',
      'profession_id' => $this->profession->id,
      'bio' => 'Programador de Laravel',
      'twitter' => 'https://twitter.com/delfinbeta',
      'role' => 'user'
    ], $custom);
  }
}
