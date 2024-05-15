<?php

namespace Database\Seeders;

use App\Constants\GlobalConstant;
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
        User::create([
            'name' => "Administrator",
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin2024'),
            'role' => GlobalConstant::ROLE_ADMIN,
        ]);
        User::create([
            'name' => "Manager 1",
            'email' => 'manager1@gmail.com',
            'password' => Hash::make('manager2024'),
            'role' => GlobalConstant::ROLE_APPROVER,
        ]);
        User::create([
            'name' => "Manager 2",
            'email' => 'manager2@gmail.com',
            'password' => Hash::make('manager2024'),
            'role' => GlobalConstant::ROLE_APPROVER,
        ]);
        User::create([
            'name' => "Manager 3",
            'email' => 'manager3@gmail.com',
            'password' => Hash::make('manager2024'),
            'role' => GlobalConstant::ROLE_APPROVER,
        ]);
        User::create([
            'name' => "Driver",
            'email' => 'driver@gmail.com',
            'password' => Hash::make('driver2024'),
            'role' => GlobalConstant::ROLE_DRIVER,
        ]);
        User::create([
            'name' => "Driver 1",
            'email' => 'driver1@gmail.com',
            'password' => Hash::make('driver2024'),
            'role' => GlobalConstant::ROLE_DRIVER,
        ]);
    }
}
