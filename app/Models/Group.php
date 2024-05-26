<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    public $table = 'groups';
    public $timestamps = true;

    protected $fillable = [
        'id',
        'name',
        'grade_id',
    ];

    protected $casts = [
        'id' => 'string',
        'name' => 'string',
        'grade_id' => 'string',
    ];
}
