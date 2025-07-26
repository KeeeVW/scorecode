<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignRoleToUser extends Command
{
    protected $signature = 'assign:role {roleName} {email}';

    protected $description = 'Assign a role to a user.';

    public function handle()
    {
        $roleName = $this->argument('roleName');
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found.");
            return Command::FAILURE;
        }

        $role = Role::findByName($roleName);

        if (!$role) {
            $this->error("Role '{$roleName}' not found. Please ensure it exists.");
            return Command::FAILURE;
        }

        $user->assignRole($role);

        $this->info("Role '{$roleName}' assigned to user: {$user->name} ({$user->email}).");

        return Command::SUCCESS;
    }
} 