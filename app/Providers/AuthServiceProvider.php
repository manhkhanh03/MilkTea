<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Policies\UserPolicy;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Model' => 'App\Policies\UserPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        
        Gate::define('is-vendor', function ($user) {
            return $user->role_id == 2;
        });

        Gate::define('is-delivery_staff', function ($user) {
            return $user->role_id == 3;
        });

        Gate::define('is-admin', function ($user) {
            return $user->role_id == 4;
        });

        Gate::define('is-customer', function ($user) {
            return $user->role_id == 1;
        });
    }
}
