<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'role'              => config('roles.ADMIN'),
                'name'              => 'admin',
                'email'             => 'admin@test.com',
                'password'          => bcrypt('111111'),
                'email_verified_at' => Carbon::now(),
            ],
            [
                'role'              => config('roles.CUSTOMER'),
                'name'              => 'customer',
                'email'             => 'customer@test.com',
                'password'          => bcrypt('111111'),
                'email_verified_at' => Carbon::now(),
            ],
            [
                'role'              => config('roles.CUSTOMER'),
                'name'              => 'customer2',
                'email'             => 'customer2@test.com',
                'password'          => bcrypt('111111'),
                'email_verified_at' => Carbon::now(),
            ],
        ];

        foreach ($users as $user) {
            if (User::whereEmail($user['email'])->count() === 0) {
                $role = $user['role'];
                unset($user['role']);
                $record = User::factory()->create($user);
                $record->assignRole($role);
            } else {
                echo "Skipping {$user['email']} as they already exist\n";
            }
        }
    }
}
