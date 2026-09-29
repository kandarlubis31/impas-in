<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $kandar = User::create([
            'name' => 'Kandar Lubis',
            'email' => 'kandar@test.com',
            'password' => Hash::make('password'),
            'age' => 21,
            'balance' => 100
        ]);

        $helper = User::create([
            'name' => 'Budi Helper',
            'email' => 'budi@test.com',
            'password' => Hash::make('password'),
            'age' => 20,
            'balance' => 0
        ]);

        Task::create([
            'creator_id' => $kandar->id,
            'title' => 'Bantu Install Laravel',
            'description' => 'Butuh bantuan setup docker di homeserver',
            'effort_level' => 3,
            'base_reward' => 50,
            'status' => 'open'
        ]);
    }
}