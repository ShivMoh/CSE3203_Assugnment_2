<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Exports\ArrayExport;
use Illuminate\Support\Facades\Validator;
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
use App\Http\Controllers\GroupController;


class GradeController extends Controller
{
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
        $grade = Grade::where("id", $group->grade_id)->orderBy("id", "asc")->get()[0];
        $grade_sections = GradeSection::where("grade_id", $group->grade_id)->orderBy("id", "asc")->get();
        $assessment = Assessment::where("id", $grade->assessment_id)->get()[0];
        $comment = $this->create_if_not_exist_comment($grade->id);
        $sections = Section::where('assessment_id', $assessment->id)->orderBy("id", "asc")->get();
        $group_controller = new GroupController();
        $students = $group_controller->get_all_students($group_id);
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
    
    public function import_grades(Request $request) {

        // for reading back in previously 
        // requires assessment id --> 
            // could get that from using one of the students usi
            // since the same student cannot be in one or more grps per assessment

        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx',
            'group_id' => 'required | string'
        ]);

        if($validator->fails()) {
            return redirect()->route('edit-grades')->withErrors($validator)->withInput();
        }
        
        $excel_data = Excel::toArray([], $request->file('file'));
        
        $headings = $excel_data[0][1];

        $total_index = count($headings) - 4;
        $percentage_index = count($headings) - 3;
        $contribution_award_index = count($headings) - 2;
        $comment_index = count($headings) - 1;
        $sections_start_index = 4;
        $sections_end_index = count($headings) - 4 - 5;

        // $sections = array_slice(
        //     $headings,
        //     $sections_start_index,
        //     $sections_end_index + 1
        // );  

        // $index = $headings[0];
        // $last_name = $headings[1];
        // $first_name = $headings[2];
        // $usi = $headings[3];
        // $total = $headings[$total_index];
        // $percentage = $headings[$percentage_index];
        // $contribution = $headings[$contribution_award_index];
        // $comment = $headings[$comment_index];

        // foreach($sections as $section) {
        //     $sect = explode("-", $section);

        //     $section_name = $sect[0];
        //     $section_marks_allocated = $sect[1];
        // }

    
        $group_id = $request->input('group_id');
      
        $data = $this->get_data($group_id);
        $data['comment']->comment = $excel_data[0][2][$comment_index];
        $data['comment']->save();
        $x = 0;
        for ($i=2; $i < count($data['students']) + 2; $i++) { 
            $data['students'][$x]['contribution']->percentage = $excel_data[0][$i][$contribution_award_index];
            $data['students'][$x]['student']->first_name = $excel_data[0][$i][2];
            $data['students'][$x]['student']->last_name = $excel_data[0][$i][1];
            $data['students'][$x]['student']->usi = $excel_data[0][$i][3];
            $data['students'][$x]['contribution']->save();
            $data['students'][$x]['student']->save();
            $x++;
        }

        $start = $sections_start_index;

        foreach ($data['grade_sections'] as $key => $grade_section) {
            if((float) $excel_data[0][2][$start] > (float) $data['sections'][$key]->marks_allocated) {
                $grade_section->marks_attained = $data['sections'][$key]->marks_allocated;
            } else {
                $grade_section->marks_attained = $excel_data[0][2][$start];
            }
            $start++;
            $grade_section->save();
        }

        return redirect()->back()->withInput();
    }

    public function export_grades(Request $request) {

        // get all sections and their names
        // get associated grade and students
        $validator = Validator::make($request->all(), [
            'group_id' => 'required | string'
        ]);

        if($validator->fails()) {
            return redirect()->route('edit-grades')->withErrors($validator)->withInput();
        }
        
        $headings = [
            "Index",
            "Last Name",
            "First Name",
            "USI"
        ];

        $data_arr = array();

        $group_id = $request->input('group_id');
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

        $blank_arr = array();
        foreach ($headings as $heading) {
            array_push($blank_arr, " ");   
        }
        array_push($data_arr, $blank_arr);
        array_push($data_arr, $headings);
        for ($i=0; $i < $group_length; $i++) { 
            $arr = array();
            array_push($arr, $i);
            array_push($arr, $data['students'][$i]['student']->last_name);
            array_push($arr, $data['students'][$i]['student']->first_name);
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

}
