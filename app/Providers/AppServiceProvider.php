<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Notification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Global Notifications Data
        view()->composer('*', function ($view) {
            $lastSeenAt = null;
            if(isset(auth()->user()->last_seen_at) && !empty(auth()->user()->last_seen_at)) {
                $lastSeenAt = auth()->user()->last_seen_at;
            }
            $notificationsCount = Notification::when(!empty($lastSeenAt), function($query) use ($lastSeenAt) {
                return $query->where('created_at', '>', $lastSeenAt);
              })->count();
            $view->with('notificationsCount', $notificationsCount);
        });
    }
}
