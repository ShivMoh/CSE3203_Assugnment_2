<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class CourseController extends Controller
{
    public function get_all_courses() {

        $courses = $this->retrieve_all_courses();
        return view('courses/courses', [
            'courses' => $courses
        ]);
    }

    public function retrieve_all_courses() {
        $user = Auth::user();
        $user_id = $user->id;

        $courses = Course::where("user_id", $user_id)->orderBy('id', 'asc')->get();
        return $courses;
    }

    public function get_course($course_id) {
        return Course::where("id", $course_id)->get()->first();
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

    public function getCoursePublic($id){
        return $this->getCourseById($id);
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

    public function searchCourses(Request $request) {
        $searchTerm = $request->input('search');
        $user = Auth::user();
        $user_id = $user->id;

        $courses = Course::where('user_id', $user_id)
            ->where(function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhere('code', 'LIKE', '%' . $searchTerm . '%');
            })
            ->orderBy('id', 'asc')
            ->get();

        return view('courses/courses', [
            'courses' => $courses
        ]);
    }

    public function editCourseName(Request $request)
    {
        $course = $this->getCourseById($request->input('course_id'));

        if (!$course) {
            return redirect()->back()->with('error', 'Course not found.');
        }

        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required|string',
                'code' => 'required|string'
            ]);

            $course->name = $request->input('name');
            $course->code = $request->input('code');
            $course->save();

            return redirect()->intended('/courses')->with('success', 'Course updated successfully.');
        }

        return view('courses/edit-courses');
    }

    public function viewCourseEditPage(Request $request)
    {
        $request->validate([
            'course_id' => 'required'
        ]);

        $course = $this->getCourseById($request->input('course_id'));

        if (!$course) {
            return redirect()->back()->with('error', 'Course not found.');
        }

        return view('courses/edit-courses', [
            'course' => $course
        ]);
    }

}
