<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;


class CourseController extends Controller
{
    public function get_all_courses() {
        $courses = Course::orderBy('id', 'asc')->get();
        return view('courses', [
            'courses' => $courses
        ]);
    }
}
