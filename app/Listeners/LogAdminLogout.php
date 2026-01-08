<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Services\ActivityLogService;

class LogAdminLogout
{
    public function handle(Logout $event): void
    {
        // Pastikan user memiliki role admin
        if ($event->user && $event->user->role === 'admin') {
            ActivityLogService::log('logout', 'Admin berhasil logout');
        }
    }
}
