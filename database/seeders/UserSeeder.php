<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\User\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $providerNames = [
            'John Smith', 'Jane Doe', 'Michael Johnson', 'Emily Davis', 'William Brown',
            'Olivia Wilson', 'James Taylor', 'Sophia Martinez', 'Benjamin Anderson', 'Isabella Thomas',
            'Daniel Jackson', 'Mia White', 'Matthew Harris', 'Charlotte Lewis', 'David Walker',
            'Amelia Hall', 'Joseph Young', 'Harper King', 'Samuel Wright', 'Evelyn Scott',
            'Andrew Green', 'Abigail Adams', 'Christopher Baker', 'Ella Nelson', 'Joshua Carter',
            'Avery Mitchell', 'Ryan Perez', 'Scarlett Roberts', 'Nathan Turner', 'Victoria Phillips',
            'Anthony Campbell', 'Grace Parker', 'Jacob Evans', 'Chloe Edwards', 'Alexander Collins',
            'Lily Stewart', 'Ethan Sanchez', 'Hannah Morris', 'Mason Rogers', 'Aria Reed'
        ];

        foreach ($providerNames as $index => $name) {
            User::create([
                'name' => $name,
                'email' => 'provider' . ($index + 1) . '@example.com', 
                'password' => Hash::make('password'), 
                'role' => Role::Provider,
            ]);
        }

        $this->command->info('40 predefined provider users created.');
    }
}
