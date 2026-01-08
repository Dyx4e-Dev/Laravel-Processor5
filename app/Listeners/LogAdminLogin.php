<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Services\ActivityLogService;

class LogAdminLogin
{
    public function handle(Login $event): void
    {
        // Pastikan user memiliki role admin
        if ($event->user && $event->user->role === 'admin') {
            ActivityLogService::log('login', 'Admin berhasil login');
        }
    }
}
