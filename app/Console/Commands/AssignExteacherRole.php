<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignExteacherRole extends Command
{
    protected $signature = 'assign:exteacher {email}';

    protected $description = 'Assign the exteacher role to a user.';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found.");
            return Command::FAILURE;
        }

        $exteacherRole = Role::findByName('exteacher');

        if (!$exteacherRole) {
            $this->error('Exteacher role not found. Please ensure it exists.');
            return Command::FAILURE;
        }

        $user->assignRole($exteacherRole);

        $this->info("Exteacher role assigned to user: {$user->name} ({$user->email}).");

        return Command::SUCCESS;
    }
} 