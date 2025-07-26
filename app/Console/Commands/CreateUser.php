<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    protected $signature = 'create:user {name} {email} {password}';

    protected $description = 'Create a new user.';

    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');

        $existingUser = User::where('email', $email)->first();

        if ($existingUser) {
            $this->error("User with email {$email} already exists.");
            return Command::FAILURE;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info("User '{$user->name}' ({$user->email}) created successfully.");

        return Command::SUCCESS;
    }
} 