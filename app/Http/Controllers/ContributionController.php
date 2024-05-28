<?php

namespace App\Http\Controllers;
use App\Models\Contribution;

use Illuminate\Http\Request;

class ContributionController extends Controller
{
    public function get_contributions_for_group($group_id) {
        $contributions = Contribution::where('group_id', $group_id)->get();
        return $contributions;
    }

    public function update_percentage($model, $percentage) {
        $model->percentage = $percentage;
        $model->save();
        return;
    }
}
