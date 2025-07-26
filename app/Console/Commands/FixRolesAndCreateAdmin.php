<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class FixRolesAndCreateAdmin extends Command
{
    protected $signature = 'fix:roles-and-admin {--remove-email=} {--create-admin=} {--admin-password=}';
    protected $description = 'Remove all roles from a user and assign exstudent, and/or create a new admin user.';

    public function handle()
    {
        // Remove all roles from a user and assign exstudent
        if ($this->option('remove-email')) {
            $email = $this->option('remove-email');
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->roles()->detach();
                $exstudentRole = Role::firstOrCreate([
                    'name' => 'exstudent',
                    'guard_name' => 'web'
                ]);
                $user->roles()->attach($exstudentRole);
                $this->info("All roles removed from {$email} and exstudent assigned.");
            } else {
                $this->error("User with email {$email} not found.");
            }
        }

        // Create a new admin user
        if ($this->option('create-admin') && $this->option('admin-password')) {
            $email = $this->option('create-admin');
            $password = $this->option('admin-password');
            $name = 'Admin';
            $user = User::where('email', $email)->first();
            if (!$user) {
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($password),
                ]);
                $this->info("Admin user created: {$email}");
            } else {
                $this->info("User {$email} already exists. Assigning admin role.");
            }
            $adminRole = Role::firstOrCreate([
                'name' => 'admin',
                'guard_name' => 'web'
            ]);
            $user->roles()->sync([$adminRole->id]);
            $this->info("Admin role assigned to {$email}");
        }
    }
} 