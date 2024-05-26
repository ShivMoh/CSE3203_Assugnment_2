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
        // Schema::table('courses', function(Blueprint $table ) {

        // });

        Schema::table('assessments', function(Blueprint $table ) {
            $table->uuid('course_id');
            $table->uuid('category_id');
            
            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('category_id')->references('id')->on('categories');
        });
        

        Schema::table('sections', function(Blueprint $table ) {
            $table->uuid('assessment_id');
            
            $table->foreign('assessment_id')->references('id')->on('assessments');
        });

        Schema::table('contributions', function(Blueprint $table ) {
            $table->uuid('group_id');
            $table->uuid('student_id');
            
            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('student_id')->references('id')->on('students');

        });

        Schema::table('groups', function(Blueprint $table ) {
            $table->uuid('grade_id');

            $table->foreign('grade_id')->references('id')->on('grades');
        });

        Schema::table('grades', function(Blueprint $table ) {
            $table->uuid('assessment_id');

            $table->foreign('assessment_id')->references('id')->on('assessments');
        });
        
        Schema::table('grade_sections', function(Blueprint $table ) {
            $table->uuid('grade_id');
            $table->uuid('section_id');

            $table->foreign('grade_id')->references('id')->on('grades');
            $table->foreign('section_id')->references('id')->on('sections');

        });
        


        Schema::table('comments', function(Blueprint $table ) {
            $table->uuid('grade_id');

            $table->foreign('grade_id')->references('id')->on('grades');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('assessments', function(Blueprint $table ) {
            $table->dropForeign(['course_id']);
            $table->dropForeign(['category_id']);
        });


        Schema::table('grades', function(Blueprint $table ) {
            $table->dropForeign(['assessment_id']);
        });

        Schema::table('sections', function(Blueprint $table ) {
            $table->dropForeign(['assessment_id']);
        });

        Schema::table('comments', function(Blueprint $table ) {
            $table->dropForeign(['grade_id']);
        });


        Schema::table('groups', function(Blueprint $table ) {
            $table->dropForeign(['grade_id']);
        });

        Schema::table('grade_sections', function(Blueprint $table ) {
            $table->dropForeign(['section_id']);
            $table->dropForeign(['grade_id']);
        });

        Schema::table('contributions', function(Blueprint $table ) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['group_id']);
        });
        
    }
};
