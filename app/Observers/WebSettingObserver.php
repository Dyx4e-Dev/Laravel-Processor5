<?php

namespace App\Observers;

use App\Models\WebSetting;
use App\Services\ActivityLogService;

class WebSettingObserver
{
    public function created(WebSetting $setting): void
    {
        ActivityLogService::logCreate('WebSetting', $setting->toArray());
    }

    public function updated(WebSetting $setting): void
    {
        $changes = $setting->getChanges();
        ActivityLogService::logUpdate('WebSetting', $setting->id ?? 'N/A', $changes);
    }

    public function deleted(WebSetting $setting): void
    {
        ActivityLogService::logDelete('WebSetting', $setting->toArray());
    }
}
