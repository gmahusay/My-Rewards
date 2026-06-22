<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Business
        User::create([
            'name' => 'Business User',
            'email' => 'business@example.com',
            'password' => Hash::make('password'),
            'role' => 'business',
        ]);

        // Employee
        User::create([
            'name' => 'Employee User',
            'email' => 'employee@example.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
        ]);

        // Customer
        User::create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        // Referrer
        User::create([
            'name' => 'Referrer User',
            'email' => 'referrer@example.com',
            'password' => Hash::make('password'),
            'role' => 'referrer',
        ]);
    }
}
