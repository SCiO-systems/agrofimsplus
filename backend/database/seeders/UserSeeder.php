<?php

namespace Database\Seeders;

use App\Enums\IdentityProvider;
use App\Models\User;
use DB;
use Illuminate\Database\Seeder;
use Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();

        $email = 'fairscribe@scio.systems';
        $password = 'scio';

        // Create the main user.
        User::create([
            'firstname' => 'Scio',
            'lastname' => 'Systems',
            'email' => $email,
            'password' => bcrypt($password),
            'identity_provider' => IdentityProvider::SCRIBE
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
