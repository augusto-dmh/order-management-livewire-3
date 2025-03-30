<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Country;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Database\Seeders\CategoryProductSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CountriesSeeder::class,
        ]);

        if (app()->environment('local', 'development')) {
            Product::factory(50)->create(function () {
                return ['country_id' => Country::inRandomOrder()->value('id')];
            });
            Category::factory(20)->create();
            $this->call([CategoryProductSeeder::class]);
        }
    }
}
