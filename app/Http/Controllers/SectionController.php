<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Assessment;
use App\Models\Section;


class SectionController extends Controller
{
    public function index(){}

    /* This is being called in  your Assessment Controller */
    // public function addSection($name, $marks){
    //     $assessment_id = Session::get('assessment_id');
    //     if ($assessment_id)
    //     {
    //         $section = Section::create([
    //             'id'=>Str::uuid(),
    //             'title'=>$name,
    //             'marks_allocated'=>$marks,
    //             'assessment_id'=>$assessment_id
    //         ]);

    //         $section->save();
    //     } else {
    //         /* Decide on an error protocol */
    //         abort(404, 'Cannot save section.');
    //     }    
        
    // }

    public function addSection($name, $marks)
    {
        Session::forget('errors');
        // Get the assessment ID from the session
        $assessmentId = Session::get('assessment_id');

        // Get the assessment
        $assessment = Assessment::find($assessmentId);
        if (!$assessment) {
            return redirect()->route('assignments')->withErrors('Assessment not found.');
        }

        // Get the total marks of the assessment
        $totalMarks = $assessment->total_marks;

        // Calculate the used marks in the existing sections
        $usedMarks = Section::where('assessment_id', $assessmentId)->sum('marks_allocated');

        // Calculate the remaining marks
        $remainingMarks = $totalMarks - $usedMarks;

        // Check if the new section's marks exceed the remaining marks
        if ($marks > $remainingMarks) {
            return redirect()->back()->withErrors('The marks for this section exceed the remaining available marks.');
        }

        // Create the new section
        $section = new Section();
        $section->id = Str::uuid();
        $section->title = $name;
        $section->marks_allocated = $marks;
        $section->assessment_id = $assessmentId;
        $section->save();

        
        // Redirect back with success message
        return redirect()->route('assessment-details')->with('success', 'Section added successfully.');
    }


    public function deleteSectionById(Request $request){
        $request->validate([
            'id' => 'required|string|exists:sections,id'
        ]);
        $section_id = $request->id;

        $section = $this->getSectionById($section_id);
        if (!$section){
            abort(404, 'Cannot find Section.');
        }

        $section->delete();
        return redirect()->route('assessment-details');
    }

    /* Getters */

    public function getSectionByAssessmentId(){
        $id = Session::get('assessment_id');

        return Section::where('assessment_id', $id)->get();
    }

    private function getSectionById($id){
        return Section::where('id', $id)->first();
    }
}
