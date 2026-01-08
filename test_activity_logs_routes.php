<?php
// Test script untuk memverifikasi filter dan delete functionality
// Jalankan dari: php artisan tinker atau copy ke routes/console.php

use Illuminate\Support\Facades\DB;

// Check existing routes
echo "=== Routes Check ===\n";
$routes = [
    'admin.activity_logs' => null,
    'admin.activity_logs.destroy' => null,
];

$routeCollection = app('router')->getRoutes();
foreach ($routeCollection as $route) {
    if (isset($routes[$route->getName()])) {
        $routes[$route->getName()] = $route;
    }
}

foreach ($routes as $name => $route) {
    echo $name . ": " . ($route ? "✓ EXISTS" : "✗ MISSING") . "\n";
    if ($route) {
        echo "  Method: " . implode(',', $route->getMethods()) . "\n";
        echo "  Path: " . $route->getPath() . "\n";
    }
}

// Check latest logs
echo "\n=== Latest Activity Logs ===\n";
$logs = DB::table('admin_activity_logs')
    ->latest('created_at')
    ->limit(5)
    ->get(['id', 'admin_id', 'activity', 'description', 'created_at']);

foreach ($logs as $log) {
    echo "\nLog ID: {$log->id}\n";
    echo "  Admin ID: {$log->admin_id}\n";
    echo "  Activity: {$log->activity}\n";
    echo "  Description: " . substr($log->description, 0, 100) . "...\n";
    echo "  Created: {$log->created_at}\n";
}

// Check filter scopes
echo "\n=== Filter Scope Test ===\n";
$adminCount = DB::table('admin_activity_logs')->where('admin_id', 1)->count();
echo "Logs for admin_id=1: {$adminCount}\n";

$createCount = DB::table('admin_activity_logs')->where('activity', 'create')->count();
echo "Logs with activity='create': {$createCount}\n";

$dateCount = DB::table('admin_activity_logs')
    ->whereDate('created_at', '>=', date('Y-m-d', strtotime('-7 days')))
    ->whereDate('created_at', '<=', date('Y-m-d'))
    ->count();
echo "Logs in last 7 days: {$dateCount}\n";

echo "\n✓ All tests completed\n";
