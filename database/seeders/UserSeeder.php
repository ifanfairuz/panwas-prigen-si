<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::count() <= 0) {
            $user = User::factory()->create([
                'name' => "Ifan Fairuz",
                'email' => "ifanfairuz@gmail.com",
                'password' => Hash::make("password"),
            ]);
            $user->ownedTeams()->save(Team::forceCreate([
                'user_id' => $user->id,
                'name' => "Super User",
                'personal_team' => false,
                'super' => true,
            ]));
            $user->ownedTeams()->save(Team::forceCreate([
                'user_id' => $user->id,
                'name' => explode(' ', $user->name, 2)[0]."'s Team",
                'personal_team' => true,
            ]));
        }
    }
}
