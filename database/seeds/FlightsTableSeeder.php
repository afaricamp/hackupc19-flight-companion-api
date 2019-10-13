<?php

use Illuminate\Database\Seeder;
Use Faker\Factory as faker;

class FlightsTableSeeder extends Seeder
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
            $flight = new \App\Flight();
            $flight->number = 'VY-' . $faker->numberBetween(2000,8000);
            $flight->score = $faker->numberBetween($min = 10000, $max = 200000);
            $flight->multiplier = $faker->randomFloat($nbMaxDecimals = 1, $min = 0, $max = 30);
            $flight->airport_id = $i;
            $flight->save();
        }
    }
}
