<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Define the users to be created
        $users = [
            ['name' => 'Jane Smith', 'email' => 'jane1234@example.com', 'phone_number' => '1234567891', 'avatar' => '/images/avatar2.png', 'password' => 'janepassword'],
            ['name' => 'Alice Johnson', 'email' => 'alice@example.com', 'phone_number' => '1234567892', 'avatar' => '/images/avatar3.png', 'password' => 'alicepassword'],
            ['name' => 'Bob Brown', 'email' => 'bob@example.com', 'phone_number' => '1234567893', 'avatar' => '/images/avatar4.png', 'password' => 'bobpassword'],
            ['name' => 'Charlie Davis', 'email' => 'charlie@example.com', 'phone_number' => '1234567894', 'avatar' => '/images/avatar5.png', 'password' => 'charliepassword'],
            ['name' => 'Diana Evans', 'email' => 'diana@example.com', 'phone_number' => '1234567895', 'avatar' => '/images/avatar6.png', 'password' => 'dianapassword'],
        ];

        // Loop through users and create them
        foreach ($users as $index => $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']), // Hash the custom password before storing
                'phone_number' => $userData['phone_number'],
                'avatar' => $userData['avatar'],
                'email_verified_at' => now(),
            ]);

            // Assign different roles based on the index
            if ($index == 0) {
                // First user will be assigned the 'Admin' role
                $user->assignRole('Admin');
            } elseif ($index == 1) {
                // Second user will be assigned the 'Manager' role
                $user->assignRole('Manager');
            } else {
                // All other users will be assigned the 'User' role
                $user->assignRole('User');
            }
        }
    }
}
