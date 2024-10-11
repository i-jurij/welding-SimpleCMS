<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        \App\Models\User::factory()->create([
            'name' => Str::random(10),
            'email' => Str::random(10).'@com.com',
            'status' => 'admin',
            'password' => Hash::make('password'),
        ]);

        \App\Models\Contacts::insert([
            ['type' => 'tlf',
            'data' => '+7 978 000 11 22', ],
            ['type' => 'tlf',
            'data' => '+7 978 000 11 23', ],
            ['type' => 'vk',
            'data' => 'vk_user_name', ],
            ['type' => 'telegram',
            'data' => 'telegram_user_name', ],
        ]);
    }
}
