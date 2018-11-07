<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeUserController extends Controller
{
    public function index($nombre) {
    		$nombre = ucfirst($nombre);

    		return "Bienvenido {$nombre}";
    }

    public function index2($nombre, $apodo) {
    		$nombre = ucfirst($nombre);

    		return "Bienvenido {$nombre}, tu apodo es {$apodo}";
    }
}
