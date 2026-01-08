<?php

namespace App\Observers;

use App\Models\Benchmark;
use App\Services\ActivityLogService;

class BenchmarkObserver
{
    public function created(Benchmark $benchmark): void
    {
        ActivityLogService::logCreate('Benchmark', $benchmark->toArray());
    }

    public function updated(Benchmark $benchmark): void
    {
        $changes = $benchmark->getChanges();
        ActivityLogService::logUpdate('Benchmark', $benchmark->id, $changes);
    }

    public function deleted(Benchmark $benchmark): void
    {
        ActivityLogService::logDelete('Benchmark', $benchmark->toArray());
    }
}
