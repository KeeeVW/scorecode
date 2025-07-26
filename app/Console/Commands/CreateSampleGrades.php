<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Course;
use App\Models\Grade;

class CreateSampleGrades extends Command
{
    protected $signature = 'grades:create-samples';

    protected $description = 'Creates sample grades for testing.';

    public function handle()
    {
        $students = User::role('exstudent')->get();
        $courses = Course::all();

        if ($students->isEmpty() || $courses->isEmpty()) {
            $this->error('Please ensure you have at least one student and one course before creating sample grades.');
            return;
        }

        // Delete existing sample grades to avoid duplicates
        Grade::whereNotNull('comments')->delete();

        foreach ($students as $student) {
            foreach ($courses as $course) {
                // Create a sample grade for each student in each course
                Grade::create([
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                    'degree' => rand(50, 100),
                    'comments' => 'Sample grade for testing',
                    'appeal_status' => 'none',
                    'appeal_reason' => null,
                ]);
            }
        }

        $this->info('Sample grades created successfully.');
    }
} 