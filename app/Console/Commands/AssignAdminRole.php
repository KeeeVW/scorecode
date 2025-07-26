<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;

class AssignAdminRole extends Command
{
    protected $signature = 'assign:admin {email}';
    protected $description = 'Assign the admin role to a user';

    public function handle()
    {
        $email = $this->argument('email');
        
        try {
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $this->error("User with email {$email} not found.");
                return;
            }

            $adminRole = Role::firstOrCreate([
                'name' => 'admin',
                'guard_name' => 'web'
            ]);

            $user->roles()->sync([$adminRole->id]);
            
            $this->info("Admin role assigned successfully to {$email}");
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
} 