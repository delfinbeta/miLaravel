<?php

use App\Profession;
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

        // DB::table('professions')->insert([
        //     'title' => 'Frontend Developer',
        // ]);

        // Profession::create([
        //     'title' => 'Prueba Borrar',
        // ]);

        Profession::create([
            'title' => 'Frontend Developer',
        ]);

        Profession::create([
            'title' => 'Backend Developer',
        ]);

        Profession::create([
            'title' => 'Diseñador Web',
        ]);

        // DB::table('professions')->where('id', 1)->delete();

        // factory(Profession::class, 6)->create();
    }
}
