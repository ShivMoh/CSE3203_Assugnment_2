<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    public $table = 'assessments';
    public $timestamps = true;

    protected $fillable = [
        'id',
        'title',
        'description',
        'total_marks',
        'course_weight',
        'course_id',
        'category_id'
    ];

    protected $casts = [
        'id' => 'string',
        'title' => 'string',
        'description' => 'string',
        'total_marks'=> 'string',
        'course_weight' => 'float',
        'course_id' => 'string',
        'category_id' => 'string'
    ];
}
