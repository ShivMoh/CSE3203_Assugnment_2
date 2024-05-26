<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    public $table = 'sections';
    public $timestamps = true;

    protected $fillable = [
        'id',
        'title',
        'marks_allocated',
        'assessment_id'
    ];

    protected $casts = [
        'id' => 'string',
        'title' => 'string',
        'marks_allocated' => 'float',
        'assessment_id'=> 'string'
    ];
}
