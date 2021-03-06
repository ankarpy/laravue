<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'url',
        'avatar'
    ];

    public function questions()
    {
      return $this->hasMany(Question::class);
    }

    public function getUrlAttribute() {
      return '#';
    }

    public function answers(){
        return $this->hasMany(Answer::class);
    }

    public function getAvatarAttribute() {
        $email = $this->email;
        $size = 32;

        return "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?s=" . $size;
    }

    public function favorites()
    {
        return $this->belongsToMany(Question::class, 'favorites')->withTimestamps(); //, 'author_id', 'question_id');
    }

    public function voteQuestions()
    {
        return $this->morphedByMany(Question::class, 'votable');
    }

    public function questionUserVote(Question $question)
    {
        $voteQuestions = $this->voteQuestions();

        return $this->_getUserVote($voteQuestions, $question);
    }

    public function voteAnswers()
    {
        return $this->morphedByMany(Answer::class, 'votable');
    }

    public function answerUserVote(Answer $answer)
    {
        $voteAnswers = $this->voteAnswers();

        return $this->_getUserVote($voteAnswers, $answer);
    }



    /* NOTE: #WHERE - We define the voting functionality in USER class, because it is an user related action */
    /* $user->vote($post, 1); */

    public function voteQuestion(Question $question, $vote)
    {
        $voteQuestions = $this->voteQuestions();

        return $this->_vote($voteQuestions, $question, $vote);
    }

    public function voteAnswer(Answer $answer, $vote)
    {
        $voteAnswers = $this->voteAnswers();

        return $this->_vote($voteAnswers, $answer, $vote);
    }

    private function _vote($relationship, $model, $vote)
    {
        if ($relationship->where('votable_id', $model->id)->exists()) {
            $relationship->updateExistingPivot($model, ['vote' => $vote]);
        }
        else {
            $relationship->attach($model, ['vote' => $vote]);
        }

        $model->load('votes');
        $downVotes = (int) $model->downVotes()->sum('vote');
        $upVotes = (int) $model->upVotes()->sum('vote');

        $model->vote_count = $upVotes + $downVotes;
        $model->save();

        return $model->vote_count;
    }

    private function _getUserVote($relationship, $model)
    {
        if ($relationship->where('votable_id', $model->id)->exists()) {
            $model->load('votes');
            $userVote = (int) $model->userVotes()->sum('vote');

            return $userVote;

        }

        return 0;
    }

}
