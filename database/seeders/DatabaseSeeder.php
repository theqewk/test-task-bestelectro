<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Создаем отделы
        $departments = [
            ['name' => 'Технический отдел'],
            ['name' => 'Финансовый отдел'],
            ['name' => 'Отдел продаж'],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }

        // Создаем тестовых пользователей
        $users = [
            [
                'name' => 'Иван Технический',
                'position' => 'Разработчик',
                'email' => 'tech@example.com',
                'password' => Hash::make('password'),
                'department_id' => 1,
            ],
            [
                'name' => 'Мария Финансист',
                'position' => 'Бухгалтер',
                'email' => 'finance@example.com',
                'password' => Hash::make('password'),
                'department_id' => 2,
            ],
            [
                'name' => 'Петр Продажник',
                'position' => 'Менеджер по продажам',
                'email' => 'sales@example.com',
                'password' => Hash::make('password'),
                'department_id' => 3,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
