<?php

use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    factory(\App\Team::class)->create(['name' => 'Tecno D 2.0']);

    factory(\App\Team::class)->times(49)->create();
  }
}
