<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;


use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Group;
use App\Models\Contribution;
use App\Models\Student;
use App\Models\Grade;
use App\Models\GradeSection;
use App\Models\Assessment;
use App\Models\Comment;
use App\Models\Section;


class GroupController extends Controller
{
    public function view_groups() {
        $groups = $this->get_all_groups();
        $student_data = array();
        foreach ($groups as $group) {
            $data = $this->get_all_students($group->id);
            array_push($student_data, $data);
        }
        
        return view(
            'groups/group-reports',
            [
                'groups' => $groups,
                'student_data'=>$student_data
            ]
        );
    }

    public function edit_grades(Request $request) {

        $validator = Validator::make($request->all(), [
            'group_id'=>'required | string',
        ]);

 
        if( $validator->fails() && empty(session('group')) || $request->isMethod('GET') && empty(session('group'))) {
            return redirect()->route('group-reports')->withErrors($validator)->withInput();
        }

        if($request->has('group_id')) {
            session([
                'group' => [
                    'group_id' => $request->input('group_id')
                ]
            ]);
        }
        
        $group_id = session('group')['group_id'];
        
        $grade_data = $this->get_grade_data($group_id);

        return view(
            'groups/grade',
            $grade_data
        );
    }

    public function update_grades(Request $request) {

        // validate this and add checks so that the marks
        // can't be more than the total allocated marks
        $validator = Validator::make($request->all(), [
            'grade_id'=>'required | string',
            'section_id' => 'required | string',
            'marks' => 'required | numeric ',
            'marks_allocated' => 'required | numeric '

        ]);

        if($validator->fails()) {
            return redirect()->route('edit-grades')->withErrors($validator)->withInput();
        }

        if((float) $request->input('marks') > (float) $request->input('marks_allocated')) {
            return redirect()->route('edit-grades')->withErrors(['marks_overflow' => 'Marks must be lower or equal to marks allocated'])->withInput();
        }

        $this->recalculate_grades(
            $request->input('grade_id'), 
            $request->input('section_id'), 
            $request->input('marks')
        );
       return redirect()->route('edit-grades');
    }

    public function update_comment(Request $request) {
        $validator = Validator::make($request->all(), [
            'grade_id'=>'required | string'
        ]);

        if($validator->fails()) {
            return redirect()->route('edit-grades')->withErrors($validator)->withInput($request->all());
        }

        $comment = $this->get_comment($request->input('grade_id'));

        if(!empty($request->input('comment'))) {
            $comment->comment = $request->input('comment');
        } else {
            $comment->comment = "No comment";
        }
        $comment->save();

        return redirect()->route('edit-grades');
    }

    private function get_comment($grade_id) {
        $comment = Comment::where('grade_id', $grade_id)->get();

        if(count($comment) == 0) return false;

        return $comment[0];
    }

    private function create_if_not_exist_comment($grade_id) {

        $comment = $this->get_comment($grade_id);
        
        if(!$comment) {
            $comment = new Comment([
                "id"=> (string) Str::uuid(),
                "comment"=>0
                ]
            );
        } 

        $comment->save();
        return $comment;
    }

    private function recalculate_grades($grade_id, $grade_section_id, $updated_section_score) {
        $grade = Grade::where("id", $grade_id)->get()[0];
        $sections = GradeSection::where("grade_id", $grade_id)->get();
        
        $new_total = 0;
        foreach ($sections as $section) {
            if($section->id == $grade_section_id) {
                $section->marks_attained = $updated_section_score;
                $new_total += $updated_section_score;
                $section->save();
            } else {
                $new_total += $section->marks_attained;
            }
        }

        $grade->marks_attained = $new_total;
        $grade->save();
    }


    private function get_grade_data($group_id) {
        $group = Group::where("id", $group_id)->get()[0];
        $grade = Grade::where("id", $group->grade_id)->get()[0];
        $grade_sections = GradeSection::where("grade_id", $group->grade_id)->get();
        $assessment = Assessment::where("id", $grade->assessment_id)->get()[0];
        $comment = $this->create_if_not_exist_comment($grade->id);
        $sections = Section::where('assessment_id', $assessment->id)->get();
        $students = $this->get_all_students($group_id);
        return [
            "comment"=>$comment,
            "group"=>$group,
            "grade"=>$grade,
            "grade_sections"=>$grade_sections,
            "sections"=>$sections,
            "assessment"=>$assessment,
            "students"=>$students
        ];
    }



    private function get_group_by_id($group_id) {
        return Group::where('id', $group_id)->get();
    }
    private function get_all_groups() {
        return Group::orderBy('id', 'ASC')->get();
    }


    private function get_all_students($group_id) {
        $contribution_plus_students = array();
        $contributions = Contribution::where('group_id', $group_id)->get();
        
        foreach ($contributions as $contribution) {
            $data = [
                "student"=>(Student::where('id', $contribution->student_id)->get())[0],
                "contribution"=>$contribution
            ];

            array_push($contribution_plus_students, $data);
        }

        return $contribution_plus_students;
    }
}
