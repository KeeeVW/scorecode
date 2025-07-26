<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixAutoIncrement extends Command
{
    protected $signature = 'fix:auto-increment';
    protected $description = 'Fix the auto-increment value for the users table';

    public function handle()
    {
        try {
            // Get the maximum ID from the users table
            $maxId = DB::table('users')->max('id') ?? 0;
            
            // Reset the auto-increment value
            DB::statement("ALTER TABLE users AUTO_INCREMENT = " . ($maxId + 1));
            
            $this->info('Auto-increment value has been reset successfully.');
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
} 