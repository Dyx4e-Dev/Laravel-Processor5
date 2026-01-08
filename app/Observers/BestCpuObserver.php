<?php

namespace App\Observers;

use App\Models\BestCpu;
use App\Services\ActivityLogService;

class BestCpuObserver
{
    public function created(BestCpu $cpu): void
    {
        ActivityLogService::logCreate('BestCpu', $cpu->toArray());
    }

    public function updated(BestCpu $cpu): void
    {
        $changes = $cpu->getChanges();
        ActivityLogService::logUpdate('BestCpu', $cpu->id, $changes);
    }

    public function deleted(BestCpu $cpu): void
    {
        ActivityLogService::logDelete('BestCpu', $cpu->toArray());
    }
}
