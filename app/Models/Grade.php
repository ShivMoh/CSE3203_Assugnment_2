<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;
    
    public $table = 'grades';
    public $timestamps = true;

    protected $fillable = [
        'id',
        'marks_attained',
        'letter_grade',
        'assessment_id'
    ];

    protected $casts = [
        'id' => 'string',
        'marks_attained' => 'float',
        'letter_grade' => 'string',
        'assessment_id'=> 'string'
    ];
}
