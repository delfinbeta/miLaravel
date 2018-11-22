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

        // $this->assertEqual(0, User::count());

        $this->assertDatabaseMissing('users', [
            'email' => 'dkbetancourt@gmail.com',
        ]);
    }

    public function test_it_loads_edit_user()
    {
        $this->get('/usuarios/5/edit')
                ->assertStatus(200)
                ->assertSee('Editar Usuario 5');
    }

    public function test_404_error_user_not_found()
    {
        $this->get('/usuarios/999')
                ->assertStatus(404)
                ->assertSee('Página no encontrada');
    }
}
