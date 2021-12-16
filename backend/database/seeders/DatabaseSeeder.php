<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Seed with test data if the environment is local or dev.
        if (App::environment('local')) {
            $this->call([
                UserSeeder::class,
                InitialSeeder::class
            ]);
        }

        if (App::environment('development')) {
            $this->call([UserSeeder::class]);
        }
    }
}
