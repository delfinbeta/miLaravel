<?php

use App\{User, UserProfile, Profession, Skill, Team};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
  protected $professions;
  protected $skills;
  protected $teams;

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

    // DB::insert('INSERT INTO users (profession_id, name, email, password) VALUES (:pId, :name, :email, :password)', [
    //   'pId' => $professionId,
    //   'name' => 'Dayancita Betancourt',
    //   'email' => 'dkbetancourt@gmail.com',
    //   'password' => bcrypt('dayan123')
    // ]);

    // DB::table('users')->insert([
    //   'profession_id' => $professionId,
    // 	'name' => 'Dayan Betancourt',
    // 	'email' => 'dkbetancourt@gmail.com',
    // 	'password' => bcrypt('dayan123')
    // ]);

    // $user = factory(User::class)->create([
    //   'team_id' => $this->teams->firstWhere('name', 'Tecno D 2.0'),
    //   'name' => 'Dayan Betancourt',
    //   'email' => 'dkbetancourt@gmail.com',
    //   'password' => bcrypt('dayan123'),
    //   'role' => 'admin',
    //   'created_at' => now()->addDay()
    // ]);

    // User::create([
    //   'profession_id' => $professionId,
    //   'name' => 'Dulayne Rosales',
    //   'email' => 'dulayney@gmail.com',
    //   'password' => bcrypt('123456')
    // ]);

    // User::create([
    //   'profession_id' => 4,
    //   'name' => 'Alfredo Cubillos',
    //   'email' => 'allfredo@gmail.com',
    //   'password' => bcrypt('123456')
    // ]);

    // factory(User::class)->create([
    //   'profession_id' => $professionId,
    //   'name' => 'Dulayne Rosales',
    //   'email' => 'dulayney@gmail.com',
    //   'password' => bcrypt('123456')
    // ]);

    // factory(User::class, 999)->create()->each(function($user) use ($professions, $skills) {
    //   $randomSkills = $skills->random(rand(0, 6));

    //   $user->skills()->attach($randomSkills);

    //   factory(\App\UserProfile::class)->create([
    //     'user_id' => $user->id,
    //     'profession_id' => rand(0, 2) ? $professions->random()->id : null
    //   ]);
    // });

    // foreach(range(1, 999) as $i) {
    //   $user = factory(User::class)->create([
    //     'team_id' => rand(0, 2) ? null : $this->teams->random()->id
    //   ]);

    //   $user->skills()->attach($this->skills->random(rand(0, 6)));

    //   factory(UserProfile::class)->create([
    //     'user_id' => $user->id,
    //     'profession_id' => rand(0, 2) ? $this->professions->random()->id : null
    //   ]);
    // }

    $this->fetchRelations();

    $user = $this->createAdmin();

    foreach(range(1, 999) as $i) {
      $user = $this->createRandomUser();
    }
  }

  protected function fetchRelations()
  {
    $this->professions = Profession::all();

    $this->skills = Skill::all();

    $this->teams = Team::all();
  }

  protected function createAdmin()
  {
    $admin = factory(User::class)->create([
      'team_id' => $this->teams->firstWhere('name', 'Tecno D 2.0'),
      'first_name' => 'Dayan',
      'last_name' => 'Betancourt',
      'email' => 'dkbetancourt@gmail.com',
      'password' => bcrypt('dayan123'),
      'role' => 'admin',
      'created_at' => now()->addDay(),
      'active' => true
    ]);

    $admin->skills()->attach($this->skills);

    // $admin->profile()->create([
    //   'bio' => 'Programador, emprendedor y líder de comunidad',
    //   'profession_id' => $this->professions->firstWhere('title', 'Backend Developer')->id
    // ]);
    $admin->profile()->update([
      'bio' => 'Programador, emprendedor y líder de comunidad',
      'profession_id' => $this->professions->firstWhere('title', 'Backend Developer')->id
    ]);
  }

  protected function createRandomUser()
  {
    $user = factory(User::class)->create([
      'team_id' => rand(0, 2) ? null : $this->teams->random()->id,
      'active' => rand(0, 3) ? true : false,
      'created_at' => now()->subDays(rand(1, 90))
    ]);

    $user->skills()->attach($this->skills->random(rand(0, 6)));

    // factory(UserProfile::class)->create([
    //   'user_id' => $user->id,
    //   'profession_id' => rand(0, 2) ? $this->professions->random()->id : null
    // ]);
    $user->profile->update([
      'profession_id' => rand(0, 2) ? $this->professions->random()->id : null
    ]);
  }
}
