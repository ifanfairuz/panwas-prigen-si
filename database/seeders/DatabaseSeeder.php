<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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

        // $user = \App\Models\User::factory()->create([
        //     'name' => "Ifan Fairuz",
        //     'email' => "ifanfairuz@gmail.com",
        //     'password' => Hash::make("password"),
        // ]);
        // $user->ownedTeams()->save(\App\Models\Team::forceCreate([
        //     'user_id' => $user->id,
        //     'name' => explode(' ', $user->name, 2)[0]."'s Team",
        //     'personal_team' => true,
        // ]));
    }
}
