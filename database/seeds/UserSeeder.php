<?php

use App\Profession;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $profession = DB::select('SELECT id FROM professions WHERE title="Frontend Developer"');

        // $professionId = DB::table('professions')->whereTitle('Backend Developer')->value('id');

        $professionId = Profession::whereTitle('Backend Developer')->value('id');

        // DB::insert('INSERT INTO users (profession_id, name, email, password) VALUES (:pId, :name, :email, :password)', [
        //     'pId' => $professionId,
        //     'name' => 'Dayancita Betancourt',
        //     'email' => 'dkbetancourt@gmail.com',
        //     'password' => bcrypt('dayan123')
        // ]);

        // DB::table('users')->insert([
        //     'profession_id' => $professionId,
        // 	'name' => 'Dayan Betancourt',
        // 	'email' => 'dkbetancourt@gmail.com',
        // 	'password' => bcrypt('dayan123')
        // ]);

        User::create([
            'profession_id' => $professionId,
            'name' => 'Dayan Betancourt',
            'email' => 'dkbetancourt@gmail.com',
            'password' => bcrypt('dayan123')
        ]);
    }
}
