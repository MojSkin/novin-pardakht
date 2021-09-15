<?php

namespace Database\Seeders;

use App\Models\Author;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        for ($i=0; $i<20; $i++) {
            Author::create([
                'f_name' => $faker->firstName,
                'l_name' => $faker->lastName,
            ]);
        }
    }
}
