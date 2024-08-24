<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonResource extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'type',
        'source',
        'file',
        'link',
        'lesson_id',
    ];
    protected $appends = ['link'];

    public function getLinkAttribute($file){
        if($this->type == 'upload'){
            return url('storage/' . $this->attributes['link']);
        }
    }
}
