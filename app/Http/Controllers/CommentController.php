<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Comment;


class CommentController extends Controller
{
    public function get_comment($grade_id) {
        $comment = Comment::where('grade_id', $grade_id)->get();

        if(count($comment) == 0) return false;

        return $comment[0];
    }


    public function create_if_not_exist_comment($grade_id) {

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

}
