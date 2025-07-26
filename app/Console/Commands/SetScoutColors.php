<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class SetScoutColors extends Command
{
    protected $signature = 'scouts:set-colors';
    protected $description = 'Set custom primary and secondary colors for each scout user.';

    public function handle()
    {
        $colors = [
            'asad' => ['#ff0000', '#ffff00'], // red x yellow
            'cupra' => ['#808080', '#808080'], // grey x grey
            'fahd' => ['#ff0000', '#8f00ff'], // red x violet
            'toor' => ['#ff0000', '#8f00ff'], // red x violet
            'saar' => ['#89cff0', '#ffff00'], // baby blue x yellow
            'deeb' => ['#ff0000', '#000000'], // red x black
        ];
        foreach ($colors as $name => [$primary, $secondary]) {
            $user = User::whereRaw('LOWER(name) = ?', [strtolower($name)])->first();
            if ($user && $user->scoutProfile) {
                $user->scoutProfile->update([
                    'theme_primary' => $primary,
                    'theme_secondary' => $secondary,
                ]);
                $this->info("Updated $name: $primary x $secondary");
            } else {
                $this->warn("User or profile not found for $name");
            }
        }
        $this->info('All specified scout colors updated.');
    }
} 