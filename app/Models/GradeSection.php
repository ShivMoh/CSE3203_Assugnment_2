<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeSection extends Model
{
    use HasFactory;

    public $table = 'grade_sections';
    public $timestamps = true;

    protected $fillable = [
        'id',
        'marks_attained',
        'grade_id',
        'section_id'
    ];

    protected $casts = [
        'id' => 'string',
        'marks_attained' => 'float',
        'grade_id' => 'string',
        'section_id'=> 'string'
    ];
}
