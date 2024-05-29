<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    public $table = 'students';
    public $timestamps = true;
    public $incrementing = false;
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'usi'
    ];

    protected $casts = [
        'id' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'usi' => 'string'
    ];
}
