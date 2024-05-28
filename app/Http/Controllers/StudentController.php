<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Contribution;
use App\Models\Student;
use App\Models\Grade;
use App\Models\GradeSection;
use App\Models\Assessment;
use App\Models\Comment;
use App\Models\Section;
use App\Models\Course;

use Illuminate\Http\Request;

use App\Http\Controllers\ContributionController;


class StudentController extends Controller
{
    public function get_students_for_group($group_id) {
        $contribution_plus_students = array();
        $contributions = (new ContributionController)->get_contributions_for_group($group_id);
        
        foreach ($contributions as $contribution) {
            $data = [
                "student"=>(Student::where('id', $contribution->student_id)->get())[0],
                "contribution"=>$contribution
            ];

            array_push($contribution_plus_students, $data);
        }

        return $contribution_plus_students;
    }

    public function update_bio_data($model, $first_name, $last_name, $usi) {
        $model->first_name = $first_name;
        $model->last_name = $last_name;
        $model->usi = $usi;

        $model->save();
        return; 
    }

}
