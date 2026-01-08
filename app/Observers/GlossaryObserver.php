<?php

namespace App\Observers;

use App\Models\Glossary;
use App\Services\ActivityLogService;

class GlossaryObserver
{
    public function created(Glossary $glossary): void
    {
        ActivityLogService::logCreate('Glossary', $glossary->toArray());
    }

    public function updated(Glossary $glossary): void
    {
        $changes = $glossary->getChanges();
        ActivityLogService::logUpdate('Glossary', $glossary->id, $changes);
    }

    public function deleted(Glossary $glossary): void
    {
        ActivityLogService::logDelete('Glossary', $glossary->toArray());
    }
}
