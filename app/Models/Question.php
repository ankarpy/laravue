<?php

namespace App\Models;

use App\Traits\VotableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory, VotableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'body'];

    protected $with = ['user'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['created_date', 'is_favorited', 'favorite_count', 'voted'];

    public function user() {
      return $this->belongsTo(User::class);
    }

    public function setTitleAttribute($value){
      $this->attributes['title'] = $value;
      $this->attributes['slug'] = \Str::slug($value);
    }

    public function getUrlAttribute() {
      return route("questions.show", $this->slug);
    }

    public function getCreatedDateAttribute() {
      return $this->created_at->diffForHumans();
    }

    public function getStatusAttribute() {
        if ($this->answer_count > 0) {
            if ($this->accepted_answer_id){
                return "answered-accepted";
            }
            return "answered";
        }
        return "unanswered";

    }

    public function getBodyHtmlAttribute()
    {
        return clean($this->bodyHtml());
    }

    public function answers(){
        return $this->hasMany(Answer::class);
    }

    /* NOTE: #WHERE - We define the accepting functionality in QUESTION class, because it is a QUESTION related action */

    public function acceptAnswer(Answer $answer)
    {
        $this->accepted_answer_id = $answer->id;
        $this->save();
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps(); //, 'question_id', 'user_id');
    }

    public function isFavorited()
    {
        return $this->favorites()->where('user_id', auth()->id())->count() > 0;
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    public function getFavoriteCountAttribute()
    {
        return $this->favorites->count();
    }


    public function getExcerptAttribute()
    {
        return $this->excerpt(250);
    }

    public function excerpt($length)
    {
        return \Str::limit(strip_tags($this->bodyHtml()), $length);
    }

    private function bodyHtml()
    {
        return \Parsedown::instance()->text($this->body);
    }

    public function getVotedAttribute()
    {

        $vote = NULL;
        if ($user = \Auth::user()){
            $vote = $user->questionUserVote($this);

        }

        return $vote !== NULL ? $vote : 0;
    }



}
