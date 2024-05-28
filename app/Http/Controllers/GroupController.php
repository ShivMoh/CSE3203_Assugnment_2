<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;


use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

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
use App\Http\Controllers\StudentController;



class GroupController extends Controller
{
  

    public function view_groups(Request $request) {

        $assessment_id = "";
        $course_id = "";
        $assessment = null;

        if (!empty($request->input('search'))) {
            $groups = $this->get_group_by_name($request->input('search'));
        } else if (!empty($request->input('assessments'))) {
            $groups = $this->get_all_groups_for_assessment($request->input('assessments'));
            $assessment_id = $request->input('assessments');
            $assessment = (new AssessmentController)->getAssessmentById($assessment_id);         
            session('course')['assessment'] = $assessment;
        }else if (!empty($request->input('courses'))) {
            $groups = $this->get_all_groups_for_course($request->input('courses'));
            $course_id = $request->input('courses');
        }  else {
            $groups = $this->get_all_groups();
        }

        $student_data = array();
        foreach ($groups as $group) {
            $data = (new StudentController)->get_students_for_group($group->id);
            array_push($student_data, $data);
        }

    
        $courses = array();
        $course_id = "";
        if(empty($request->input('courses'))) {
            if(Session::has("course_id")) {
                $course_id = Session::get('course_id');
            } else {
                // we'll take the first retrieved course as the default
                $courses = (new CourseController)->retrieve_all_courses();
                $course_id = $courses[0]->id;
                
                if (!Session::has("course")) {
                    session([
                        'course' => [
                            'course_id' => $course_id
                        ]
                    ]);
                } else {
                    $course_id = session('course')['course_id'];
                }
            }
        } else {
            $courses = (new CourseController)->retrieve_all_courses();
            $course_id = $request->input('courses');
            session('course')['course_id'] = $course_id;
            
        }

        $assessments = (new AssessmentController)->getAssessmentByCourseId($course_id);
        $course = (new CourseController)->get_course($course_id);
        
        return view(
            'groups/group-reports',
            [
                'groups' => $groups,
                'student_data'=>$student_data,
                'assessments'=>$assessments,
                'courses'=>$courses,
                'c'=>$course,
                'a'=>$assessment
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
        $group = Group::where("id", $request->input('group_id'))->get()[0];

        if(!$group->exists()) {
            abort(404, 'Cannot delete group.');
        }

        $contributions = Contribution::where("group_id", $group_id)->get();

        foreach ($contributions as $contribution) {
            Student::where("id", $contribution->student_id)->delete();
            $contribution->delete();
        }
        
        // $group = Group::where("id", $request->input('group_id'))->get();
        Grade::where("id", $group->grade_id)->delete();

        return redirect()->route('group-reports');

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

    private function get_all_groups_for_course($course_id) {
        $assessments = Assessment::where('course_id', $course_id)->get();
        
        $groups = array();

        foreach ($assessments as $assessment) {
            $grades = Grade::where('assessment_id', $assessment->id)->get();
            foreach ($grades as $grade) {
                $associated_group = Group::where('grade_id', $grade->id)->get()[0];
                array_push($groups, $associated_group);
            }
        }

        return $groups;
       
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
