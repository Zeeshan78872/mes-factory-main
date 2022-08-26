<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Helper;

class UserLoggedOut
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
    public function handle(Logout $event)
    {
        Helper::logSystemActivity('Auth', 'User logged out successfully!');
    }
}
