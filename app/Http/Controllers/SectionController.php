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
    public function addSection($name, $marks){
        $assessment_id = Session::get('assessment_id');
        if ($assessment_id)
        {
            
            $section = Section::create([
                'id'=>Str::uuid(),
                'title'=>$name,
                'marks_allocated'=>$marks,
                'assessment_id'=>$assessment_id
            ]);

            $section->save();
        } else {
            /* Decide on an error protocol */
            abort(404, 'Cannot save section.');
        }    
        
    }

    

    /* Getters */
    public function getSectionByAssessmentId(){
        $id = Session::get('assessment_id');

        return Section::where('assessment_id', $id)->get();
    }
}
