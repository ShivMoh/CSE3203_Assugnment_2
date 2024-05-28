<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class CourseController extends Controller
{
    public function get_all_courses() {

        $user = Auth::user();
        $user_id = $user->id;

        $courses = Course::where("user_id", $user_id)->orderBy('id', 'asc')->get();
        return view('courses/courses', [
            'courses' => $courses
        ]);
    }

    public function addCourse(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string'
        ]);

        $course = Course::create([
            'user_id' => $user->id,
            'id' => Str::uuid(),
            'name' => $request->name,
            'code' => $request->code
        ]);

        return redirect()->intended('/courses');
    }

    public function viewAdd(){
        $courses = $this->getAllCourses();
        return view('courses.courses-add', [
            'courses'=>$courses
        ]);
    }

    private function getCourseById($id){
        $course = Course::where('id', $id)->get()->first();
        return $course;
    }

    private function getAllCourses(){
        return Course::orderBy('id', 'ASC')->get();
    }

    public function deleteCourseById(Request $request){
        $request->validate([
            'course_id' => 'required|string|exists:courses,id'
        ]);

        $course = $this->getCourseById($request->input('course_id'));
        if (!$course->exists()){
            abort(404, 'Cannot delete course.');
        }
        $course->delete();
        return redirect()->intended('/courses');
    }

}
