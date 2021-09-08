<?php

namespace App\Providers;

use App\Policies\QuestionPolicy;
use App\Policies\AnswerPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Models\Question;
use App\Models\Answer;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         Question::class => QuestionPolicy::class,
         Answer::class => AnswerPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Gates are used in complex actions, whereas policies are used for authorizing CRUD actions
        \Gate::define('access-daily-message', function($user){
            return isset($user);
        });
    }
}
