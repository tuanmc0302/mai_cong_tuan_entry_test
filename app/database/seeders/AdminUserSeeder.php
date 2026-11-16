<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (empty($email) || empty($password)) {
            throw new RuntimeException(
                'ADMIN_EMAIL and ADMIN_PASSWORD must be configured in .env'
            );
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Administrator',
                'password' => Hash::make($password),
                'gender' => 'other',
            ]
        );
    }
}
