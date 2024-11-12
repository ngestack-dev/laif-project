<?php

namespace App\Listeners;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Events\Registered;

class AssignUserRole
{
    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        // Pastikan untuk memeriksa apakah user yang terdaftar adalah instance dari User
        if ($event->user instanceof User) {
            $event->user->assignRole('user'); // Ini akan berfungsi jika User menggunakan HasRoles trait
        }
    }
}
