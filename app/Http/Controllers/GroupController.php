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
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\ExcelServiceProvider;
use App\Http\Controllers\StudentController;



class GroupController extends Controller
{


    public function import(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'group_report' => 'required|mimes:xls,xlsx'
        ]);

        // Load the file into an array
        $data = Excel::toArray([], $request->file('group_report'));

        // Get the first sheet's data
        $sheetData = $data[0];

        // Extract the headers (first row)
        $headers = array_shift($sheetData);

        // Retrieve the assessment ID from the session
        $assessmentId = Session::get('assessment_id');

        // Create a new grade for the student with the retrieved assessment ID
        $grade = Grade::create([
            'id' => Str::uuid(),
            'marks_attained' => 0, // Initialize to 0
            'letter_grade' => '', // Initialize to empty
            'assessment_id' => $assessmentId // Set to the retrieved assessment ID
        ]);


        // Create a new group
        $group = Group::create([
            'id' => Str::uuid(), // Generate a unique ID for the group
            'name' => 'New Group', // You can customize the group name as needed
            'grade_id' => $grade->id// Set to null or use an appropriate value if necessary
        ]);

        // Process each row in the sheet
        foreach ($sheetData as $row) {
            // Assuming the columns are in the order: last name, first name, usi, award
            $lastName = $row[0];
            $firstName = $row[1];
            $usi = $row[2];
            $award = $row[3];

            // Find or create the student
            $student = Student::create(
                ['id' => Str::uuid(),'usi' => $usi, 'first_name' => $firstName, 'last_name' => $lastName]
            );

            // Create the contribution for the student
            Contribution::create([
                'id' => Str::uuid(),
                'percentage' => $award, // Assuming 'award' corresponds to 'percentage'
                'group_id' => $group->id,
                'student_id' => $student->id
            ]);

            // Create an empty comment for the student
            Comment::create([
                'id' => Str::uuid(),
                'comment' => '',
                'grade_id' => $grade->id // Set to appropriate value if necessary
            ]);

            // Retrieve all sections related to the assessment_id
            $sections = Section::where('assessment_id', $assessmentId)->get();

            // Loop through each section and create a new grade section
            foreach ($sections as $section) {
                GradeSection::create([
                    'id' => Str::uuid(),
                    'name'=>$section->title,
                    'marks_attained' => 0,
                    'grade_id' => $grade->id, // Assuming $grade is already defined
                    'section_id' => $section->id
                ]);
            }
        }

        return redirect()->route('group-reports')->with('success', 'Group and contributions created successfully.');
    }

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
            
        } else {
            $courses = (new CourseController)->retrieve_all_courses();
            $course_id = $request->input('courses');
            session('course')['course_id'] = $course_id;
            
        }


        $assessments = (new AssessmentController)->getAssessmentByCourseId($course_id);

        
        $course = (new CourseController)->get_course($course_id);
        if (!$course) {
            $course = $courses[0];
        }
        
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
