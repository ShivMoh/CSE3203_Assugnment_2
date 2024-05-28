<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    public $table = 'courses';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'id',
        'name',
        'code'
    ];

    protected $casts = [
        'user_id' => 'string',
        'id' => 'string',
        'name' => 'string',
        'code'=> 'string'
    ];
}
