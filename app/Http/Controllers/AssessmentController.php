<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
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
    public function index(Request $request)
    {
        /* Searching */
        if (!empty($request->input('search'))){
            $assessments = $this->getAssessmentByName($request->input('search'));
        }
        /* Directed from Courses */
        /* Check to make sure it's named course_id */
        else if (!empty($request->input('course_id'))){
            $assessments = $this->getAssessmentByCourseId($request->input('course_id'));
        }
        else{
            $assessments = $this->getAllAssessments();
        }

        return view('assignments.assignment', ['content'=>$assessments]);
    }

    public function viewAdd(){
        $categories = $this->getAllCategories();
        $courses = $this->getAllCourses();
        return view('assignments.assignment-add', [
            'categories' => $categories,
            'courses'=>$courses
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

    public function viewAssessmentDetail(Request $request){
        $assessment = $this->getAssessmentById($request->input('id'));
        $sections = $this->getAssessmentSections($request->input('id'));
        return view('assignments.assignment-details',[
            'assessment'=>$assessment,
            'sections'=>$sections
        ]);
    }

    public function addSection(Request $request){
        $sectionController = new SectionController;
        $sectionController->addSection($request);
        $this->viewAssessmentDetail($request);
    }

    /* Getters */

    private function getAllAssessments(){
        return Assessment::orderBy('id', 'ASC')->get();
    }
    private function getAllCategories(){
        return Category::orderBy('id', 'ASC')->get();
    }
    private function getAllCourses(){
        return Course::orderBy('id', 'ASC')->get();
    }

    private function getAssessmentByName($name){
        $assessments = Assessment::where('name', 'like', '%'.$name.'%')->get();
        return $assessments;
    }

    private function getAssessmentById($id){
        $assessment = Assessment::where('id', $id)->get()->first();
        return $assessment;
    }

    private function getAssessmentbyCourseId($course_id){
        return Assessment::where('course_id', $course_id)->get();
    }

    private function getAssessmentSections($assessment_id){
        return Section::where('assessment_id', $assessment_id)->get();
    }
}
