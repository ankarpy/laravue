<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Question;
use App\Models\Answer;


class UsersQuestionsAnswersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create()->each(function($u) {
        $u->questions()
            ->saveMany(
                Question::factory(rand(1,5))->make() // MAKE generates data, and we save it manually with saveMany() // Else with CREATE it would create it..
            )
            ->each(function($q) {
                $q->answers()->saveMany(
                    Answer::factory(rand(1,5))->make()
                );
            });
    });
    }
}
