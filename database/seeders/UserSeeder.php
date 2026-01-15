<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Админ',
            'email' => '1@1.ru',
            'password' => bcrypt('111111'),
            'role_id' => 3,
        ]);

        User::factory()->create([
            'name' => 'Агент 1',
            'email' => '2@2.ru',
            'password' => bcrypt('222222'),
            'role_id' => 2,
        ]);

        User::factory()->create([
            'name' => 'Агент 2',
            'email' => '22@22.ru',
            'password' => bcrypt('222222'),
            'role_id' => 2,
        ]);

        User::factory()->create([
            'name' => 'Клиент 1',
            'email' => '3@3.ru',
            'password' => bcrypt('333333'),
            'role_id' => 1,
            'phone' => '+1234567890',
            'messenger' => 'telegram',
            'messenger_number' => '@username',
        ]);

        User::factory()->create([
            'name' => 'Клиент 2',
            'email' => '33@33.ru',
            'password' => bcrypt('333333'),
            'role_id' => 1,
            'phone' => '+9876543210',
            'messenger' => 'telegram',
            'messenger_number' => '@client2',
        ]);

        User::factory()->create([
            'name' => 'Менеджер',
            'email' => '4@4.ru',
            'password' => bcrypt('444444'),
            'role_id' => 4,
        ]);
    }
}
