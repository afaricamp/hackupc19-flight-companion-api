<?php

use Illuminate\Database\Seeder;
Use Faker\Factory as faker;

class AirportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = faker::create();
        for ($i = 1; $i <= 5; $i++){
            $airport = new \App\Airport();
            $airport->name = $faker->city . ' Airport';
            $airport->save();
        }

    }
}
