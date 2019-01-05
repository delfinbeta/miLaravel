<?php

use App\{User, Profession, Skill};
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
        // $professionId = Profession::whereTitle('Backend Developer')->value('id');
        $professions = Profession::all();

        $skills = Skill::all();

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

        $user = factory(User::class)->create([
            'name' => 'Dayan Betancourt',
            'email' => 'dkbetancourt@gmail.com',
            'password' => bcrypt('dayan123'),
            'role' => 'admin',
            'created_at' => now()->addDay()
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

        $user->profile()->create([
            'bio' => 'Programador, emprendedor y líder de comunidad',
            'profession_id' => $professions->firstWhere('title', 'Backend Developer')->id
        ]);

        // factory(User::class)->create([
        //     'profession_id' => $professionId,
        //     'name' => 'Dulayne Rosales',
        //     'email' => 'dulayney@gmail.com',
        //     'password' => bcrypt('123456')
        // ]);

        factory(User::class, 999)->create()->each(function($user) use ($professions, $skills) {
            $randomSkills = $skills->random(rand(0, 6));

            $user->skills()->attach($randomSkills);

            factory(\App\UserProfile::class)->create([
                'user_id' => $user->id,
                'profession_id' => rand(0, 2) ? $professions->random()->id : null
            ]);
        });
    }
}
