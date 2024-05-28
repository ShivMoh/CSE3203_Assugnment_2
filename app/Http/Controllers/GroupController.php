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
use App\Models\Course;
use Maatwebsite\Excel\ExcelServiceProvider;



class GroupController extends Controller
{
  

    public function view_groups(Request $request) {

        if (!empty($request->input('search'))) {
            $groups = $this->get_group_by_name($request->input('search'));
        } else if (!empty($request->input('assessments'))) {
            $groups = $this->get_all_groups_for_assessment($request->input('assessments'));
      
        }  else {
            $groups = $this->get_all_groups();
        }

        $student_data = array();
        foreach ($groups as $group) {
            $data = $this->get_all_students($group->id);
            array_push($student_data, $data);
        }

        // this should be retrieved from the session
        $course_id = "a4891a81-e1aa-4588-bdb9-9b46028347d4";

        $assessments = Assessment::where("course_id", $course_id)->get();
        
        return view(
            'groups/group-reports',
            [
                'groups' => $groups,
                'student_data'=>$student_data,
                'assessments'=>$assessments
            ]
        );
    }

    public function delete_group(Request $request) {
        $validator = Validator::make($request->all(), [
            'group_id'=>'required | string',
        ]);

 
        if( $validator->fails() && empty(session('group'))) {
            return redirect()->route('group-reports')->withErrors($validator)->withInput();
        }


        $group_id = $request->input('group_id');
        $contributions = Contribution::where("group_id", $group_id)->get();

        foreach ($contributions as $contribution) {
            Student::where("id", $contribution->student_id)->delete();
            $contribution->delete();
        }
        
        $group = Group::where("id", $request->input('group_id'))->get();
        Grade::where("id", $group[0]->grade_id)->delete();

        return redirect()->route('group-reports');

    }

    public function get_all_students($group_id) {
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


    private function get_group_by_id($group_id) {
        return Group::where('id', $group_id)->get();
    }


    private function get_group_by_name($group_name) {
        $groups = Group::where("name", 'like', '%'.$group_name.'%')->get();
        return $groups; 
    }

    private function get_all_groups() {
        return Group::orderBy('id', 'ASC')->get();
    }

    private function get_all_groups_for_assessment($assessment_id) {
        $grades = Grade::where('assessment_id', $assessment_id)->get();
        $groups = array();

        foreach ($grades as $grade) {
            $associated_group = Group::where('grade_id', $grade->id)->get()[0];
            array_push($groups, $associated_group);
        }
        
        return $groups;
    }


}
