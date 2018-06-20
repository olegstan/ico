<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $user = \Auth::user();

        
        // Auth gates for: User management
        Gate::define('user_management_access', function ($user) {
            return in_array($user->role_id, [User::ADMIN]);
        });

        // Auth gates for: Roles
        Gate::define('role_access', function ($user) {
            return in_array($user->role_id, [User::ADMIN]);
        });
        Gate::define('role_create', function ($user) {
            return in_array($user->role_id, [User::ADMIN]);
        });
        Gate::define('role_edit', function ($user) {
            return in_array($user->role_id, [User::ADMIN]);
        });
        Gate::define('role_view', function ($user) {
            return in_array($user->role_id, [User::ADMIN]);
        });
        Gate::define('role_delete', function ($user) {
            return in_array($user->role_id, [User::ADMIN]);
        });

        // Auth gates for: Users
        Gate::define('user_access', function ($user) {
            return in_array($user->role_id, [User::ADMIN]);
        });
        Gate::define('user_create', function ($user) {
            return in_array($user->role_id, [User::ADMIN]);
        });
        Gate::define('user_edit', function ($user) {
            return in_array($user->role_id, [User::ADMIN]);
        });
        Gate::define('user_view', function ($user) {
            return in_array($user->role_id, [User::ADMIN]);
        });
        Gate::define('user_delete', function ($user) {
            return in_array($user->role_id, [User::ADMIN]);
        });

        // Auth gates for: Configs
        Gate::define('config_access', function ($user) {
            return in_array($user->role_id, [User::ADMIN]);
        });
        Gate::define('config_create', function ($user) {
            return in_array($user->role_id, [User::ADMIN]);
        });
        Gate::define('config_edit', function ($user) {
            return in_array($user->role_id, [User::ADMIN]);
        });
        Gate::define('config_view', function ($user) {
            return in_array($user->role_id, [User::ADMIN]);
        });
        Gate::define('config_delete', function ($user) {
            return in_array($user->role_id, [User::ADMIN]);
        });

        // Auth gates for: Sessions
        Gate::define('session_access', function ($user) {
            return in_array($user->role_id, [User::ADMIN, User::GAMER]);
        });
    }
}
