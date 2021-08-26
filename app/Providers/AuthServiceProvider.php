<?php

namespace App\Providers;

use App\Policies\QuestionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Models\Question;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         Question::class => QuestionPolicy::class,
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
