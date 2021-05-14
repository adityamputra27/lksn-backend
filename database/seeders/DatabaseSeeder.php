<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        User::create([
            'name' => 'Administrator1',
            'username' => 'admin1',
            'password' => Hash::make('admin1'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Administrator2',
            'username' => 'admin2',
            'password' => Hash::make('admin2'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'User1',
            'username' => 'user1',
            'password' => Hash::make('user1'),
            'role' => 'user'
        ]);
    }
}
