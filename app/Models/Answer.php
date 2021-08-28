<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $with = ['user'];

    public function question(){
        return $this->belongsTo(Question::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getCreatedDateAttribute() {
        return $this->created_at->diffForHumans();
    }

    public function getBodyHtmlAttribute()
    {
        return parsedown($this->body);
    }

    public static function boot(){
        parent::boot();

        static::created(function ($answer){
            $answer->question->increment('answer_count');
            $answer->question->save();
        });
    }
}
