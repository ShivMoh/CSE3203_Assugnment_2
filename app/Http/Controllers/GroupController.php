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
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Exports\ArrayExport;


class GroupController extends Controller
{
    public function test(Request $request) {

        // for reading back in previously 
        // requires assessment id --> 
            // could get that from using one of the students usi
            // since the same student cannot be in one or more grps per assessment

        
        $file_path = public_path('test/Assessment.xlsx');

        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);
        
        $data = Excel::toArray([], $request->file('file'));
        
        $headings = $data[0][1];

        $total_index = count($headings) - 4;
        $percentage_index = count($headings) - 3;
        $contribution_award_index = count($headings) - 2;
        $comment_index = count($headings) - 1;
        $sections_start_index = 4;
        $sections_end_index = count($headings) - 9;

        $sections = array_slice(
            $headings,
            $sections_start_index,
            $sections_end_index + 1
        );

        $index = $headings[0];
        $last_name = $headings[1];
        $first_name = $headings[2];
        $usi = $headings[3];
        $total = $headings[$total_index];
        $percentage = $headings[$percentage_index];
        $contribution = $headings[$contribution_award_index];
        $comment = $headings[$comment_index];

        foreach($sections as $section) {
            $sect = explode("-", $section);
            $section_name = $sect[0];
            $section_marks_allocated = $sect[1];

            echo $section_name." ".$section_marks_allocated."\n";
        }

        return response()->json($data);
    }

    public function export() {

        // get all sections and their names
        // get associated grade and students

        $headings = [
            "Index",
            "Last Name",
            "First Name",
            "USI"
        ];

        $data_arr = array();

        $group_id = "c5bf2e4d-e949-4d24-863b-f553ca1c37d4";
        $data = $this->get_data($group_id);

        foreach ($data["sections"] as $section) {
            $section_plus_contrib = $section->title."-".$section->marks_allocated;
            array_push($headings, $section_plus_contrib);
        }

        $total_plus_score = "Total"."-".$data['assessment']->total_marks;
        array_push($headings, $total_plus_score);

        $assessment_plus_weight = "Percentage"."-".($data["assessment"]->course_weight * 100)."%";
        array_push($headings, $assessment_plus_weight);

        array_push($headings, "Contribution Award");
        array_push($headings, "Comment");

        $group_length = count($data["students"]);

        array_push($data_arr, $headings);
        for ($i=0; $i < $group_length; $i++) { 
            $arr = array();
            array_push($arr, $i);
            array_push($arr, $data['students'][$i]['student']->first_name);
            array_push($arr, $data['students'][$i]['student']->last_name);
            array_push($arr, $data['students'][$i]['student']->usi);
            foreach ($data['grade_sections'] as $grade_section) {
                array_push($arr, $grade_section->marks_attained);
            }

            $score = $data['grade']->marks_attained * ($data['students'][$i]['contribution']->percentage / 100);
            $score_fraction = $score /  $data['assessment']->total_marks;
            
            array_push($arr, $data['grade']->marks_attained * ($data['students'][$i]['contribution']->percentage / 100));
            array_push($arr, $score_fraction * ($data["assessment"]->course_weight * 100));

            array_push($arr, $data['students'][$i]['contribution']->percentage);
            array_push($arr, $data['comment']->comment);

            array_push($data_arr, $arr);
        }

        // Pass the array to the export class and download the file
        return Excel::download(new ArrayExport($data_arr), 'export.xlsx');
    }

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
        
        $grade_data = $this->get_data($group_id);

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


    private function get_data($group_id) {
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
