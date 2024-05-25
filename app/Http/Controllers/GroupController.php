<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function get_all_groups() {
        return view('groups/group-reports');
    }
}
