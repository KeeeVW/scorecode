<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            [
                'key' => 'uniform_points',
                'value' => '10',
                'description' => 'Points awarded for uniform record',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'korasa_points',
                'value' => '15',
                'description' => 'Points awarded for korasa record',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'badge_points',
                'value' => '20',
                'description' => 'Points awarded for badge record',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'attendance_points',
                'value' => '5',
                'description' => 'Points awarded for attendance record',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
}; 