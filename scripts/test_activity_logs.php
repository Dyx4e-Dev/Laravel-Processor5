<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\Team;
use App\Models\AdminActivityLog;

// login as admin id=1 if exists
if (class_exists('\App\\Models\\User')) {
    $user = \App\Models\User::find(1);
    if ($user) {
        \Illuminate\Support\Facades\Auth::loginUsingId(1);
        echo "Logged in as: " . $user->name . "\n";
    } else {
        echo "Admin user id=1 not found; proceeding unauthenticated\n";
    }
}

// Create team
$team = Team::create([
    'name' => 'Test Team ' . time(),
    'role' => 'tester',
    'email' => 'team+' . time() . '@example.test',
    'alamat' => 'Jalan Test',
    'photo' => null,
]);

echo "Created Team id={$team->id}\n";

// Update team
$team->update(['name' => $team->name . ' (edited)']);
echo "Updated Team id={$team->id}\n";

// Delete team
$id = $team->id;
$team->delete();

echo "Deleted Team id={$id}\n";

// Show last 5 logs
$logs = AdminActivityLog::latest()->take(5)->get();
foreach ($logs as $log) {
    echo "[{$log->id}] {$log->activity} - " . substr($log->description, 0, 200) . "\n";
}
