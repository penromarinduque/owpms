<?php

namespace App\Providers;

use App\Models\LtpApplication;
use App\Models\User;
use App\Policies\NotificationPolicy;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Helpers\ApplicationHelper;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 
        View::share('_helper', new ApplicationHelper);
        
        // Paginator
        Paginator::useBootstrapFive();
        //
        Gate::policy(DatabaseNotification::class, NotificationPolicy::class);

        Gate::define('view-payment-order', function ($user, $paymentOrder) {
            $isOwner = $paymentOrder->ltpApplication->permittee->user_id == $user->id;
            $permissions = $user->getUserPermissions(); // use injected $user instead of auth()->user()

            return $isOwner || in_array('PAYMENT_ORDERS_INDEX', $permissions);
        });

        // permittee gates
        Gate::define('is-owned', function (User $user, LtpApplication $ltpApplication) {
            return $ltpApplication->permittee->user_id == $user->id;
        });
    }
}
