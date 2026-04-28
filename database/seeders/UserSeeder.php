<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory([
            'name' => 'user',
            'email' => 'user@example.com',
            'password'=>'12345'
        ])->afterCreating(function (User $user) {
            $user->assignRole(RolesEnum::User->value);
        })->create();

        User::factory(['name' => "vendor", 'email' => "vendor@example.com",'password'=>'12345'])->afterCreating(function (User $user) {
            $user->assignRole(RolesEnum::Vendor->value);
        })->create();
        User::factory(['name' => 'admin', 'email' => 'admin@example.com','password'=>'12345'])->afterCreating(function (User $user) {
            $user->assignRole(RolesEnum::Admin->value);
        })->create();
    }
}
