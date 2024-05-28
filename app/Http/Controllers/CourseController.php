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

    public function viewAssignments(Request $request)
    {
        // Check if course_id exists in the request
        if ($request->has('course_id')) {
            // Store course_id in the session
            $courseId = $request->input('course_id');
            session(['course_id' => $courseId]);
        }

        // Redirect to the 'assignments' route
        return redirect()->route('assignments');
    }

}
