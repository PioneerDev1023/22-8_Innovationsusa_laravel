<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

          Gate::define('update_product_master', function ($user) {
            return $user->hasPermission('update_product_master');
          });
          Gate::define('view_client_dashboard', function ($user) {
            return $user->hasPermission('view_client_dashboard');
          });
          Gate::define('export_guests', function ($user) {
            return $user->hasPermission('export_guests');
          });
          Gate::define('view_form_data', function ($user) {
            return $user->hasPermission('view_form_data');
          });
    }
}
