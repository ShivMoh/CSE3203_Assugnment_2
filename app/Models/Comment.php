<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public $table = 'comments';
    public $timestamps = true;

    protected $fillable = [
        'id',
        'comment',
        'grade_id'
    ];

    protected $casts = [
        'id' => 'string',
        'comment' => 'string',
        'grade_id'=> 'string'
    ];
}
