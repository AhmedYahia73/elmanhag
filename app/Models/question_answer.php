<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class question_answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'answer' ,
        'true_answer' ,
        'question_id' ,
    ];
}
