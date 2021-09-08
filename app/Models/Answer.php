<?php

namespace App\Models;

use App\Traits\VotableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory, VotableTrait;

    protected $fillable = ['body', 'user_id'];

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
        return clean(\Parsedown::instance()->text($this->body));
    }

    public static function boot(){
        parent::boot();

        static::created(function ($answer){
            $answer->question->increment('answer_count');
        });

        static::deleted(function ($answer){
            $question = $answer->question;
            $question->decrement('answer_count');
            // Instead of this, use mysql referencing #MYSQLREF
            /*if ($question->accepted_answer_id === $answer->id){
                $question->accepted_answer_id = NULL;
                $question->save();
            }*/
        });
    }

    public function getStatusAttribute()
    {
        return $this->isAccepted ? 'vote-accepted' : '';
    }

    public function getIsAcceptedAttribute()
    {
        return $this->isAccepted();
    }

    public function isAccepted()
    {
        return $this->id === $this->question->accepted_answer_id;
    }



}
