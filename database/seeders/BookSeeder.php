<?php

namespace Database\Seeders;

use App\Models\Book;
use Faker\Factory;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        for ($i=0; $i<100; $i++) {
            Book::create([
                'isbn' => $faker->firstName,
                'l_name' => $faker->lastName,
            ]);
        }
    }
}
