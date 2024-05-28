<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Assessment;
use App\Models\Category;
use App\Models\Course;
use App\Models\Section;


class AssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     $assessments = [];
    //     /* Searching */
    //     if (($request->has('search')) && (filled($request->input('search'))) ){
    //         $assessments = $this->getAssessmentByName($request->input('search'));
    //     }
    //     /* Directed from Courses */
    //     elseif ((Session::has('course_id'))) {
    //         $courseId = Session::get('course_id');
    //         $assessments = $this->getAssessmentByCourseId($courseId);
    //     }
    //     else{
    //         $assessments = $this->getAllAssessments();
    //     }

    //     return view('assignments.assignment', ['content'=>$assessments]);
    // }

    public function index(Request $request)
    {
        $assessments = [];

        // Check if the request is a search request
        if ($request->has('search') && filled($request->input('search'))) {
            $assessments = $this->getAssessmentByName($request->input('search'));
        }
        // Check if the session has a course_id
        elseif (Session::has('course_id')) {
            // Check if the referer was the 'courses' route
            if ($request->headers->get('referer') && str_contains($request->headers->get('referer'), route('courses'))) {
                $courseId = Session::get('course_id');
                $assessments = $this->getAssessmentByCourseId($courseId);
            } else {
                // If the referer is not from 'courses', you might want to handle it differently
                // For example, you could ignore the course_id in the session
                $assessments = $this->getAllAssessments();
            }
        }
        // Default to getting all assessments if no search or course_id is found
        else {
            $assessments = $this->getAllAssessments();
        }

        return view('assignments.assignment', ['content' => $assessments]);
    }


    public function viewAdd(Request $request){
        $assessment = $this->getCurrentAssessment();
        $type = $request->input('type');
        $categories = $this->getAllCategories();
        $courses = $this->getAllCourses();
        return view('assignments.assignment-add', [
            'categories' => $categories,
            'courses'=>$courses,
            'type'=>$type,
            'assessment'=>$assessment
        ]);
    }

    public function addAssessment(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'desc' => 'required|string',
            'marks' => 'required|string',
            'weight' => 'required|numeric',
            'course_id' => 'required|string',
            'category_id' => 'required|string'
        ]);

        $assessment = Assessment::create([
            'id' => Str::uuid(),
            'title' => $request->title,
            'description' => $request->desc,
            'total_marks' => $request->marks,
            'course_weight' => $request->weight,
            'course_id' => $request->course_id,
            'category_id' => $request->category_id
        ]);

        return redirect()->route('assignments');
    }

    public function viewAssessmentDetail(Request $request)
    {
        // Check if the request has an assessment ID
        if ($request->has('id')) {
            $assessmentId = $request->input('id');
            // Store the assessment ID in the session
            Session::put('assessment_id', $assessmentId);
        } else {
            // Check if the session has an assessment ID
            if (Session::has('assessment_id')) {
                $assessmentId = Session::get('assessment_id');
            } else {
                // Redirect to the assignments route if no ID is found
                return redirect()->route('assignments');
            }
        }

        // Fetch the assessment and sections using the assessment ID
        $assessment = $this->getAssessmentById($assessmentId);
        $sections = $this->getAssessmentSections();

        // Return the view with the assessment and sections
        return view('assignments.assignment-details', [
            'assessment' => $assessment,
            'sections' => $sections
        ]);
    }


    public function addSection(Request $request){
        $request->validate([
            'name' => 'required|string',
            'marks' => 'required|numeric',
        ]);

        $sectionController = new SectionController;
        $sectionController->addSection($request->name, $request->marks);
        return $this->viewAssessmentDetail($request);
    }

    public function deleteAssessmentById(Request $request){
        $request->validate([
            'id' => 'required|string|exists:assessments,id'
        ]);

        $assessment = $this->getAssessmentById($request->input('id'));
        if (!$assessment->exists()){
            abort(404, 'Cannot delete assignment.');
        }
        $assessment->delete();
        return redirect()->route('assignments');
    }

    /* Getters */


    private function getAllAssessments(){
        $courses = $this->getAllCourses();
        $courseIds = [];
    
        foreach ($courses as $course) {
            $courseIds[] = $course->id;
        }
    
        return Assessment::whereIn('course_id', $courseIds)->orderBy('id', 'ASC')->get();
    }
    
    
    private function getAllCategories(){
        return Category::orderBy('id', 'ASC')->get();
    }
    private function getAllCourses(){
        $user = Auth::user();
        $user_id = $user->id;

        $courses = Course::where("user_id", $user_id)->orderBy('id', 'asc')->get();
        return $courses;
    }

    private function getAssessmentByName($name){
        $assessments = Assessment::where('title', 'like', '%'. $name .'%')->get();
        return $assessments;
    }

    private function getAssessmentById($id){
        $assessment = Assessment::where('id', $id)->get()->first();
        return $assessment;
    }

    private function getCurrentAssessment()
    {
        // Retrieve the assessment ID from the session
        $assessmentId = Session::get('assessment_id');

        // Query the database to get the assessment with the retrieved ID
        $assessment = Assessment::where('id', $assessmentId)->first();

        return $assessment;
    }


    private function getAssessmentbyCourseId($course_id){
        return Assessment::where('course_id', $course_id)->get();
    }

    private function getAssessmentSections(){
        $sectionController = new SectionController;
        return $sectionController->getSectionByAssessmentId();
    }
}
