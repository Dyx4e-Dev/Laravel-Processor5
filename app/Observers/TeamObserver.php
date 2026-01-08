<?php

namespace App\Observers;

use App\Models\Team;
use App\Services\ActivityLogService;

class TeamObserver
{
    public function created(Team $team): void
    {
        ActivityLogService::logCreate('Team', $team->toArray());
    }

    public function updated(Team $team): void
    {
        $changes = $team->getChanges();
        ActivityLogService::logUpdate('Team', $team->id, $changes);
    }

    public function deleted(Team $team): void
    {
        ActivityLogService::logDelete('Team', $team->toArray());
    }
}
