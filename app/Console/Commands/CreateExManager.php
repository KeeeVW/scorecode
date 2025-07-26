<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class CreateExManager extends Command
{
    protected $signature = 'create:exmanager';
    protected $description = 'Create an exmanager account with predefined credentials';

    public function handle()
    {
        $email = 'exmanager@google.com';
        $password = 'password';
        $name = 'ExManager';

        try {
            // Check if user exists
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                // Create new user
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($password),
                ]);
                $this->info("ExManager user created: {$email}");
            } else {
                $this->info("User {$email} already exists. Updating role.");
            }

            // Get or create exmanager role
            $exmanagerRole = Role::firstOrCreate([
                'name' => 'exmanager',
                'guard_name' => 'web'
            ]);

            // Assign exmanager role
            $user->roles()->sync([$exmanagerRole->id]);
            $this->info("ExManager role assigned to {$email}");

            // Display login credentials
            $this->info("\nLogin Credentials:");
            $this->info("Email: {$email}");
            $this->info("Password: {$password}");

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
} 