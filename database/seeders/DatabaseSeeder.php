<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ScoutProfile;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        try {
            $this->call([
                AdminUserSeeder::class,
            ]);

            // Create or update admin account
            $admin = User::where('email', 'admin@wadielnilescouts.com')->first();
            if (!$admin) {
                $admin = User::create([
                    'name' => 'Admin',
                    'email' => 'admin@wadielnilescouts.com',
                    'password' => Hash::make('admin123'),
                    'is_admin' => true,
                    'profile_picture' => 'https://raw.githubusercontent.com/KeeeVW/score/refs/heads/master/WhatsApp%20Image%202024-12-22%20at%2013.58.10_9b771d24.jpg',
                    'primary_color' => '#000000',
                    'secondary_color' => '#FFFFFF',
                ]);
            } else {
                $admin->update([
                    'profile_picture' => 'https://raw.githubusercontent.com/KeeeVW/score/refs/heads/master/WhatsApp%20Image%202024-12-22%20at%2013.58.10_9b771d24.jpg',
                    'primary_color' => '#000000',
                    'secondary_color' => '#FFFFFF',
                ]);
            }

            // Create scout accounts
            $scouts = [
                [
                    'name' => 'toor',
                    'email' => 'toor@google.com',
                    'password' => 'toor303',
                    'profile_picture' => 'https://raw.githubusercontent.com/KeeeVW/score/refs/heads/master/WhatsApp%20Image%202024-11-18%20at%2013.41.30_10db4129.jpg',
                    'primary_color' => '#FF0000',
                    'secondary_color' => '#800080',
                ],
                [
                    'name' => 'saar',
                    'email' => 'saar@google.com',
                    'password' => 'saar500',
                    'profile_picture' => 'https://raw.githubusercontent.com/KeeeVW/score/refs/heads/master/WhatsApp%20Image%202024-11-18%20at%2013.41.29_f8092d8d.jpg',
                    'primary_color' => '#ADD8E6',
                    'secondary_color' => '#FFFF00',
                ],
                [
                    'name' => 'fahd',
                    'email' => 'fahd@google.com',
                    'password' => 'fahd707',
                    'profile_picture' => 'https://raw.githubusercontent.com/KeeeVW/score/refs/heads/master/WhatsApp%20Image%202024-11-18%20at%2013.41.28_4c3ee79a.jpg',
                    'primary_color' => '#800080',
                    'secondary_color' => '#000000',
                ],
                [
                    'name' => 'deeb',
                    'email' => 'deeb@google.com',
                    'password' => 'deeb101',
                    'profile_picture' => 'https://raw.githubusercontent.com/KeeeVW/score/refs/heads/master/WhatsApp%20Image%202024-11-18%20at%2013.41.29_d950e50a.jpg',
                    'primary_color' => '#FF0000',
                    'secondary_color' => '#FFFF00',
                ],
                [
                    'name' => 'asad',
                    'email' => 'asad@google.com',
                    'password' => 'asad678',
                    'profile_picture' => 'https://raw.githubusercontent.com/KeeeVW/score/refs/heads/master/WhatsApp%20Image%202024-11-18%20at%2013.41.27_41de44db.jpg',
                    'primary_color' => '#FF0000',
                    'secondary_color' => '#FFFF00',
                ],
                [
                    'name' => 'cupra',
                    'email' => 'cupra@google.com',
                    'password' => 'cupra405',
                    'profile_picture' => 'https://raw.githubusercontent.com/KeeeVW/score/refs/heads/master/WhatsApp%20Image%202024-11-18%20at%2013.41.30_8e8b378a.jpg',
                    'primary_color' => '#808080',
                    'secondary_color' => '#FFFF00',
                ],
            ];

            foreach ($scouts as $scout) {
                $user = User::where('email', $scout['email'])->first();
                if ($user) {
                    $user->update([
                        'profile_picture' => $scout['profile_picture'],
                        'primary_color' => $scout['primary_color'],
                        'secondary_color' => $scout['secondary_color'],
                    ]);
                } else {
                    $user = User::create([
                        'name' => $scout['name'],
                        'email' => $scout['email'],
                        'password' => Hash::make($scout['password']),
                        'is_admin' => false,
                        'profile_picture' => $scout['profile_picture'],
                        'primary_color' => $scout['primary_color'],
                        'secondary_color' => $scout['secondary_color'],
                    ]);
                }

                ScoutProfile::create([
                    'user_id' => $user->id,
                    'theme_primary' => $scout['primary_color'],
                    'theme_secondary' => $scout['secondary_color'],
                    'locks_remaining' => 0,
                    'is_active' => true,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Seeder error: ' . $e->getMessage());
            throw $e;
        }
    }
}
