<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Models\Assessment;
use App\Models\Category;
use App\Models\Course;
use App\Models\Section;
use PHPUnit\Framework\Constraint\IsEmpty;


class AssessmentController extends Controller
{
    //TODO
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
        Log::info('Started');
        $request->validate([
            'title' => 'required|string',
            'desc' => 'required|string',
            'marks' => 'required|string',
            'weight' => 'required|numeric',
            'course_id' => 'required|string',
            'category_id' => 'required|string'
        ]);
        Log::info('Validated');

        $assessment = Assessment::create([
            'id' => Str::uuid(),
            'title' => $request->title,
            'description' => $request->desc,
            'total_marks' => $request->marks,
            'course_weight' => $request->weight,
            'course_id' => $request->course_id,
            'category_id' => $request->category_id
        ]);
        Log::info('Saved');


        return redirect()->route('assignments');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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

    private function getAssessmentbyCourseId($course_id){
        return Assessment::where('course_id', $course_id)->get();
    }
}
