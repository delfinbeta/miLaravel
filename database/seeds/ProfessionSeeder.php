<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::insert('INSERT INTO professions (title) VALUES (:title)', ['title' => 'Frontend Developer']);

        DB::table('professions')->insert([
            'title' => 'Prueba Borrar',
        ]);

        DB::table('professions')->insert([
        	'title' => 'Frontend Developer',
        ]);

        DB::table('professions')->insert([
        	'title' => 'Backend Developer',
        ]);

        DB::table('professions')->insert([
        	'title' => 'Diseñador Web',
        ]);

        DB::table('professions')->where('id', 1)->delete();
    }
}
