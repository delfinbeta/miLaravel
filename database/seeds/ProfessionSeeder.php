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
        DB::table('professions')->insert([
        	'title' => 'Frontend Developer',
        ]);

        DB::table('professions')->insert([
        	'title' => 'Backend Developer',
        ]);

        DB::table('professions')->insert([
        	'title' => 'Diseñador Web',
        ]);
    }
}
