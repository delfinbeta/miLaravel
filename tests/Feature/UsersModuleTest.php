<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersModuleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_loads_users_list()
    {
        $this->get('/usuarios')
        		 ->assertStatus(200)
        		 ->assertSee('Usuarios')
                 ->assertSee('Zoraida')
                 ->assertSee('Dayan');
    }

    public function test_it_loads_users_list_empty()
    {
        $this->get('/usuarios?empty')
                 ->assertStatus(200)
                 ->assertSee('No hay usuarios registrados');
    }

    public function test_it_loads_users_details()
    {
        $this->get('/usuarios/5')
        		 ->assertStatus(200)
        		 ->assertSee('Mostrando detalle del usuario: 5');
    }

    public function test_it_loads_new_user()
    {
        $this->get('/usuarios/nuevo')
        		 ->assertStatus(200)
        		 ->assertSee('Crear Nuevo Usuario');
    }

    public function test_it_loads_edit_user()
    {
        $this->get('/usuarios/5/edit')
                 ->assertStatus(200)
                 ->assertSee('Editar Usuario 5');
    }
}
