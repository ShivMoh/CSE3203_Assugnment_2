<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;


class CourseController extends Controller
{
    public function get_all_courses() {
        $user_id = "101754e0-1748-4c33-8fe6-94f9c64babdb";
        $courses = Course::where("user_id", $user_id)->orderBy('id', 'asc')->get();
        return view('courses', [
            'courses' => $courses
        ]);
    }
}
