<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Helper;

class UserLoggedIn
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Login $event)
    {
        Helper::logSystemActivity('Auth','User logged in successfully!');
    }
}
