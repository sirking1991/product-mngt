<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 100) as $index) {
            DB::table('products')->insert([
                'code' => $faker->ean13,
                'name' => $faker->sentence($nbWords = 6, $variableNbWords = true),
                'url' => $faker->url,
            ]);
        }

        // duplicate names
        DB::table('products')->insert([
            'code' => $faker->ean13,
            'name' => "SAMPLE DUPLICATE NAME #1",
            'url' => $faker->url,
        ]);        
        DB::table('products')->insert([
            'code' => $faker->ean13,
            'name' => "SAMPLE DUPLICATE NAME #1",
            'url' => $faker->url,
        ]);                
        DB::table('products')->insert([
            'code' => $faker->ean13,
            'name' => "SAMPLE DUPLICATE NAME #2",
            'url' => $faker->url,
        ]);                
        DB::table('products')->insert([
            'code' => $faker->ean13,
            'name' => "SAMPLE DUPLICATE NAME #2",
            'url' => $faker->url,
        ]);                

    }
}