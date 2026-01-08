<?php

// Skrip: scripts/insert_admin_log.php
// Tujuan: bootstrap Laravel dan masukkan rekap perubahan ke tabel admin_activity_logs

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AdminActivityLog;
use Illuminate\Support\Str;

$summary = <<<'EOT'
Rekap Perubahan (sampai 2026-01-05):
- Perbaikan `resources/views/backend/edit_benchmark.blade.php`: perbaiki penggunaan `old()` untuk scores, perbaiki pemanggilan CPU/rekomendasi, hapus class `reveal` agar bagian tampil.
- Controller `app/Http/Controllers/Admin/BenchmarkController.php`: eager-load relasi `result` dan `bestCpus` pada method `edit`.
- Implementasi fitur Log Aktivitas Admin:
  - Migration: `create_admin_activity_logs_table` ditambahkan dan dimigrasi.
  - Model: `app/Models/AdminActivityLog.php` dibuat dengan relasi dan scopes.
  - Service: `app/Services/ActivityLogService.php` dibuat untuk centralisasi logging.
  - Observers & Listeners: observers untuk Benchmark/Quiz/Glossary; listeners untuk Login/Logout didaftarkan.
  - Controller & Routes & View: `AdminActivityLogController`, route `admin/log_aktivitas`, dan view `resources/views/backend/activity_logs.blade.php` ditambahkan.
- Perbaikan bug: hapus pengecekan `is_admin` (diganti pengecekan berdasarkan `role = "admin"`) di listeners dan controller terkait.
- Dokumentasi: beberapa file dokumentasi dan checklist terkait ditambahkan.

Catatan: perubahan terakhir termasuk pembaruan file view/route/layout controller terkait `activity_logs`.
EOT;

try {
    $log = AdminActivityLog::create([
        'admin_id' => 1,
        'activity' => 'system_recap',
        'description' => $summary,
        'ip_address' => '127.0.0.1',
        'user_agent' => 'script/insert_admin_log',
    ]);

    echo "OK: inserted log id={$log->id}\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}

