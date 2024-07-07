<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 50) as $index) {
            DB::table('customer')->insert([
                'zd_id' => rand(1, 100),
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'tags' => $faker->sentence,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
