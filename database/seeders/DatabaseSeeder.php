<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id' => "101754e0-1748-4c33-8fe6-94f9c64babdb",
                'name' => 'guy',
                'email' => 'guy@email.com',
                'password' => Hash::make('verystrongpassword')
            ]
        ]);

        DB::table('courses')->insert([
            [
                'id' => Str::uuid(),
                'name' => 'Introduction to Programming',
                'code' => 'CS101',
                'user_id' => '101754e0-1748-4c33-8fe6-94f9c64babdb'

            ],
            [
                'id' => Str::uuid(),
                'name' => 'Database Systems',
                'code' => 'CS102',
                'user_id' => '101754e0-1748-4c33-8fe6-94f9c64babdb'
            ],
        ]);

   
        // Seed Categories
        DB::table('categories')->insert([
            [
                'id' => Str::uuid(),
                'name' => 'Exams',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Assignments',
            ],
        ]);

        // Seed Assessments
        DB::table('assessments')->insert([
            [
                'id' => Str::uuid(),
                'title' => 'Midterm Exam',
                'description' => 'Midterm examination for CS101',
                'total_marks' => 50,
                'course_weight' => 0.3,
                'course_id' => DB::table('courses')->where('code', 'CS101')->first()->id,
                'category_id' => DB::table('categories')->where('name', 'Exams')->first()->id,
            ],
            [
                'id' => Str::uuid(),
                'title' => 'Final Exam',
                'description' => 'Final examination for CS101',
                'total_marks' => 100,
                'course_weight' => 0.5,
                'course_id' => DB::table('courses')->where('code', 'CS101')->first()->id,
                'category_id' => DB::table('categories')->where('name', 'Exams')->first()->id,
            ],
        ]);

        // Seed Students
        DB::table('students')->insert([
            [
                'id' => Str::uuid(),
                'first_name' => 'John',
                'last_name' => 'Doe',
                'usi' => '123456',
            ],
            [
                'id' => Str::uuid(),
                'first_name' => 'Jessie',
                'last_name' => 'Doe',
                'usi' => '121456',
            ],
            [
                'id' => Str::uuid(),
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'usi' => '654321',
            ],
        ]);

        // Seed Grades
        DB::table('grades')->insert([
            [
                'id' => Str::uuid(),
                'marks_attained' => 85,
                'letter_grade' => 'A',
                'assessment_id' => DB::table('assessments')->where('title', 'Midterm Exam')->first()->id,
            ],
            [
                'id' => Str::uuid(),
                'marks_attained' => 90,
                'letter_grade' => 'A+',
                'assessment_id' => DB::table('assessments')->where('title', 'Final Exam')->first()->id,
            ],
        ]);

        // Seed Groups
        DB::table('groups')->insert([
            [
                'id' => Str::uuid(),
                'name' => 'Group A',
                'grade_id' => DB::table('grades')->where('marks_attained', 85)->first()->id,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Group B',
                'grade_id' => DB::table('grades')->where('marks_attained', 90)->first()->id,
            ],
        ]);

        // Seed Contributions
        DB::table('contributions')->insert([
            [
                'id' => Str::uuid(),
                'percentage' => 50,
                'group_id' => DB::table('groups')->where('name', 'Group A')->first()->id,
                'student_id' => DB::table('students')->where('first_name', 'John')->first()->id,
            ],
            [
                'id' => Str::uuid(),
                'percentage' => 70,
                'group_id' => DB::table('groups')->where('name', 'Group A')->first()->id,
                'student_id' => DB::table('students')->where('first_name', 'Jessie')->first()->id,
            ],
            [
                'id' => Str::uuid(),
                'percentage' => 50,
                'group_id' => DB::table('groups')->where('name', 'Group B')->first()->id,
                'student_id' => DB::table('students')->where('first_name', 'Jane')->first()->id,
            ],
        ]);

        // Seed Sections
        DB::table('sections')->insert([
            [
                'id' => Str::uuid(),
                'title' => 'Section 1',
                'marks_allocated' => 30,
                'assessment_id' => DB::table('assessments')->where('title', 'Midterm Exam')->first()->id,
            ],
            [
                'id' => Str::uuid(),
                'title' => 'Section 2',
                'marks_allocated' => 20,
                'assessment_id' => DB::table('assessments')->where('title', 'Midterm Exam')->first()->id,
            ],
            [
                'id' => Str::uuid(),
                'title' => 'Section 2',
                'marks_allocated' => 50,
                'assessment_id' => DB::table('assessments')->where('title', 'Final Exam')->first()->id,
            ],
        ]);

        // Seed GradeSections
        DB::table('grade_sections')->insert([
            [
                'id' => Str::uuid(),
                'name' => "Section 1",
                'marks_attained' => 30,
                'grade_id' => DB::table('grades')->where('marks_attained', 85)->first()->id,
                'section_id' => DB::table('sections')->where('title', 'Section 1')->first()->id,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Section 2',
                'marks_attained' => 20,
                'grade_id' => DB::table('grades')->where('marks_attained', 85)->first()->id,
                'section_id' => DB::table('sections')->where('title', 'Section 1')->first()->id,
            ],
            [
                'id' => Str::uuid(),
                'name' => "Section 1",
                'marks_attained' => 50,
                'grade_id' => DB::table('grades')->where('marks_attained', 90)->first()->id,
                'section_id' => DB::table('sections')->where('title', 'Section 2')->first()->id,
            ],
        ]);

        // Seed Comments
        DB::table('comments')->insert([
            [
                'id' => Str::uuid(),
                'comment' => 'Great job!',
                'grade_id' => DB::table('grades')->where('marks_attained', 85)->first()->id,
            ],
            [
                'id' => Str::uuid(),
                'comment' => 'Excellent work!',
                'grade_id' => DB::table('grades')->where('marks_attained', 90)->first()->id,
            ],
        ]);
    }
}
