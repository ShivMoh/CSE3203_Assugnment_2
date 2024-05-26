<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::create('users', function (Blueprint $table) {
        //     $table->uuid('id')->primary();
        //     $table->string('email')->unique();
        //     $table->string('password');
        //     $table->timestamps();
        // });

        // Schema::create('user_roles', function (Blueprint $table) {
        //     $table->uuid('id')->primary();
        //     $table->string('type')->unique();
        //     $table->timestamps();
        // });

        Schema::create('courses', function(Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('code');
            $table->timestamps();

        });
        
        Schema::create('assessments', function(Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('description');
            $table->float('total_marks');
            $table->float('course_weight');
            $table->timestamps();

        });

        Schema::create('categories', function(BLueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->timestamps();

        });

        
        Schema::create('groups', function(BLueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->timestamps();

        });

        Schema::create('contributions', function(BLueprint $table) {
            $table->uuid('id')->primary();
            $table->float('percentage');
            $table->timestamps();

        });

        Schema::create('students', function(BLueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('usi');
            $table->timestamps();

        });

        Schema::create('grades', function(Blueprint $table) {
            $table->uuid('id')->primary();
            $table->float('marks_attained');
            $table->string('letter_grade');
            $table->timestamps();

        });

        Schema::create('sections', function(Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->float('marks_allocated');
            $table->timestamps();

        });


        Schema::create('grade_sections', function(Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->float('marks_attained');
            $table->timestamps();

        });

        Schema::create('comments', function(Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('comment');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
        Schema::dropIfExists('assessments');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('grades');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('grade_sections');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('contributions');
        Schema::dropIfExists('students');

    }
};
