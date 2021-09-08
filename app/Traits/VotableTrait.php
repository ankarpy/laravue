<?php
namespace App\Traits;

trait VotableTrait
{
    public function votes()
    {
        return $this->morphToMany(\App\Models\User::class, 'votable');
    }

    public function upVotes()
    {
        return $this->votes()->wherePivot('vote', 1);
    }

    public function downVotes()
    {
        return $this->votes()->wherePivot('vote', -1);
    }
}
