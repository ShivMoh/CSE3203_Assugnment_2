<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Assessment;
use App\Models\Section;


class SectionController extends Controller
{
    public function index(){}

    /* This is being called in  your Assessment Controller */
    public function addSection(Request $request){
        $request->validate([
            'name' => 'required|string',
            'marks' => 'requred|numeric',
            'id' => 'required|string'
        ]);
        $section = Section::create([
            'id'=>Str::uuid(),
            'title'=>$request->name,
            'marks_allocated'=>$request->marks,
            'assessment_id'=>$request->id
        ]);
    }
    /* Getters */
    private function getSectionByAssessmentId($id){
        return Section::where('assessment_id', $id)->get();
    }
}
