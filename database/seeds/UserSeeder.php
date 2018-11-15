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
            'password' => bcrypt('dayan123'),
            'is_admin' => true
        ]);

        // User::create([
        //     'profession_id' => $professionId,
        //     'name' => 'Dulayne Rosales',
        //     'email' => 'dulayney@gmail.com',
        //     'password' => bcrypt('123456')
        // ]);

        // User::create([
        //     'profession_id' => 4,
        //     'name' => 'Alfredo Cubillos',
        //     'email' => 'allfredo@gmail.com',
        //     'password' => bcrypt('123456')
        // ]);

        factory(User::class)->create([
            'profession_id' => $professionId,
            'name' => 'Dulayne Rosales',
            'email' => 'dulayney@gmail.com',
            'password' => bcrypt('123456')
        ]);

        factory(User::class, 10)->create();
    }
}
