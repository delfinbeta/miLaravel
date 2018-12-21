<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class UsersModuleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_loads_users_list()
    {
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

    public function test_it_loads_users_list_empty()
    {
        $this->get('/usuarios')
                ->assertStatus(200)
                ->assertSee('No hay usuarios registrados');
    }

    public function test_it_displays_users_details()
    {
        $user = factory(User::class)->create([
            'name' => 'Dayan Betancourt',
        ]);

        $this->get('/usuarios/'.$user->id)
                ->assertStatus(200)
                ->assertSee('Dayan Betancourt');
    }

    public function test_it_loads_new_user()
    {
        $this->get('/usuarios/nuevo')
                ->assertStatus(200)
                ->assertSee('Crear Nuevo Usuario');
    }

    public function test_it_creates_new_user() {
        // $this->withoutExceptionHandling();

        $this->post('/usuarios/nuevo', [
            'name' => 'Dayan Betancourt',
            'email' => 'dkbetancourt@gmail.com',
            'password' => 'dayan123'
        ])->assertRedirect('usuarios');

        // $this->assertDatabaseHas('users', [
        //     'name' => 'Dayan Betancourt',
        //     'email' => 'dkbetancourt@gmail.com',
        // ]);

        $this->assertCredentials([
            'name' => 'Dayan Betancourt',
            'email' => 'dkbetancourt@gmail.com',
            'password' => 'dayan123'
        ]);
    }

    public function test_name_required() {
        $this->from('/usuarios/nuevo')
            ->post('/usuarios/nuevo', [
                'name' => '',
                'email' => 'dkbetancourt@gmail.com',
                'password' => 'dayan123'
            ])->assertRedirect('/usuarios/nuevo')
                ->assertSessionHasErrors(['name' => 'El campo nombre es obligatorio']);

        // $this->assertEquals(0, User::count());

        $this->assertDatabaseMissing('users', [
            'email' => 'dkbetancourt@gmail.com',
        ]);
    }

    public function test_email_required() {
        $this->from('/usuarios/nuevo')
            ->post('/usuarios/nuevo', [
                'name' => 'Dayan Betancourt',
                'email' => '',
                'password' => 'dayan123'
            ])->assertRedirect('/usuarios/nuevo')
                ->assertSessionHasErrors(['email' => 'El campo email es obligatorio']);

        // $this->assertEquals(0, User::count());

        $this->assertDatabaseMissing('users', [
            'email' => 'dkbetancourt@gmail.com',
        ]);
    }

    public function test_email_invalid() {
        $this->from('/usuarios/nuevo')
            ->post('/usuarios/nuevo', [
                'name' => 'Dayan Betancourt',
                'email' => 'email_no_valido.com',
                'password' => 'dayan123'
            ])->assertRedirect('/usuarios/nuevo')
                ->assertSessionHasErrors(['email' => 'Email inválido']);

        // $this->assertEquals(0, User::count());

        $this->assertDatabaseMissing('users', [
            'email' => 'dkbetancourt@gmail.com',
        ]);
    }

    public function test_email_unique() {
        factory(User::class)->create([
            'email' => 'dkbetancourt@gmail.com'
        ]);

        $this->from('/usuarios/nuevo')
            ->post('/usuarios/nuevo', [
                'name' => 'Dayan Betancourt',
                'email' => 'dkbetancourt@gmail.com',
                'password' => 'dayan123'
            ])->assertRedirect('/usuarios/nuevo')
                ->assertSessionHasErrors(['email' => 'Email ya registrado']);

        $this->assertEquals(1, User::count());
    }

    public function test_password_required() {
        $this->from('/usuarios/nuevo')
            ->post('/usuarios/nuevo', [
                'name' => 'Dayan Betancourt',
                'email' => 'dkbetancourt@gmail.com',
                'password' => ''
            ])->assertRedirect('/usuarios/nuevo')
                ->assertSessionHasErrors(['password' => 'El campo contraseña es obligatorio']);

        // $this->assertEquals(0, User::count());

        $this->assertDatabaseMissing('users', [
            'email' => 'dkbetancourt@gmail.com',
        ]);
    }

    public function test_password_6caracteres() {
        $this->from('/usuarios/nuevo')
            ->post('/usuarios/nuevo', [
                'name' => 'Dayan Betancourt',
                'email' => 'dkbetancourt@gmail.com',
                'password' => '123'
            ])->assertRedirect('/usuarios/nuevo')
                ->assertSessionHasErrors(['password' => 'La contraseña debe contener mínimo 6 caracteres']);

        // $this->assertEquals(0, User::count());

        $this->assertDatabaseMissing('users', [
            'email' => 'dkbetancourt@gmail.com',
        ]);
    }

    public function test_it_loads_edit_user()
    {
        $user = factory(User::class)->create();

        $this->get("/usuarios/{$user->id}/edit")
                ->assertStatus(200)
                ->assertViewIs('users.edit')
                ->assertSee('Editar Usuario')
                ->assertViewHas('user', function ($viewUser) use ($user) {
                    return $viewUser->id == $user->id;
                });
    }

    public function test_404_error_user_not_found()
    {
        $this->get('/usuarios/999')
                ->assertStatus(404)
                ->assertSee('Página no encontrada');
    }
}
