<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Админ',
            'email' => '1@1.ru',
            'password' => bcrypt('111111'),
        ]);

        User::factory()->create([
            'name' => 'Агент 1',
            'email' => '2@2.ru',
            'password' => bcrypt('222222'),
        ]);

        User::factory()->create([
            'name' => 'Агент 2',
            'email' => '22@22.ru',
            'password' => bcrypt('222222'),
        ]);

        User::factory()->create([
            'name' => 'Клиент 1',
            'email' => '3@3.ru',
            'password' => bcrypt('333333'),
        ]);

        User::factory()->create([
            'name' => 'Клиент 2',
            'email' => '33@33.ru',
            'password' => bcrypt('333333'),
            RoleSeeder::class,
            UserSeeder::class,
        ]);
    }
}
