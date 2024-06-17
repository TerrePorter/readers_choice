<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // create 10 users
        User::factory(10)->create();

        // set the user token for each user
        $users = User::all(['id', 'nickname']);
        foreach ($users as $index => $userData) {
            $user = User::find($userData['id']);
            $user->user_token =  hash('crc32', $userData->nickname . '-' . $userData->id) ;
            $user->save();
        }

    }
}
