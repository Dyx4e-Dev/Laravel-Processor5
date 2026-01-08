<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$log = App\Models\AdminActivityLog::with('admin')->latest()->first();
if ($log) {
    echo "LATEST id={$log->id} activity={$log->activity} admin=" . ($log->admin?->name ?? 'System') . "\n";
} else {
    echo "NO LOGS\n";
}
