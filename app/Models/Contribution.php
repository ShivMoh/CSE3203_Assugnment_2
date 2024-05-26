<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    use HasFactory;

    public $table = 'contributions';
    public $timestamps = true;

    protected $fillable = [
        'id',
        'percentage',
        'group_id',
        'student_id'
    ];

    protected $casts = [
        'id' => 'string',
        'percentage' => 'float',
        'group_id' => 'string',
        'student_id'=> 'string'
    ];
}
