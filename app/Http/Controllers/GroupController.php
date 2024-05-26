<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Contribution;
use App\Models\Student;
use App\Models\Grade;
use App\Models\GradeSection;
use App\Models\Assessment;

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

    public function edit_grades() {
        $group_id = "ebc62fd7-f276-4b91-867c-eda18c2b7a35";
        $grade_data = $this->get_grade_data($group_id);
        return view(
            'groups/grade',
            $grade_data
        );
    }

    public function update_grades(Request $request) {
        $this->recalculate_grades(
            $request->input('grade_id'), 
            $request->input('section_id'), 
            $request->input('marks')
        );
       return $this->edit_grades();
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
        return [
            "group"=>$group,
            "grade"=>$grade,
            "grade_sections"=>$grade_sections,
            "assessment"=>$assessment
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
