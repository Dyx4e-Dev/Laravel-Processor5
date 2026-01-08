<?php

namespace App\Observers;

use App\Models\Quiz;
use App\Services\ActivityLogService;

class QuizObserver
{
    public function created(Quiz $quiz): void
    {
        ActivityLogService::logCreate('Quiz', $quiz->toArray());
    }

    public function updated(Quiz $quiz): void
    {
        $changes = $quiz->getChanges();
        ActivityLogService::logUpdate('Quiz', $quiz->id, $changes);
    }

    public function deleted(Quiz $quiz): void
    {
        ActivityLogService::logDelete('Quiz', $quiz->toArray());
    }
}
