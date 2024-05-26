<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Contribution;
use App\Models\Student;

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
