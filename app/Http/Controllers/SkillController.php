<?php

namespace App\Http\Controllers;

use App\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
  public function index()
  {
  	$title = 'Listado de Habilidades';

  	return view('skills.index', [
  		'title' => $title,
  		'skills' => Skill::orderBy('name')->get()
  	]);
  }
}
