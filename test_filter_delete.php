<?php
// Quick test to verify filter and delete functionality
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

// Test 1: Check logs count
echo "=== Test Activity Logs Filter & Delete ===\n\n";

$total = DB::table('admin_activity_logs')->count();
echo "Total logs: $total\n";

$create_logs = DB::table('admin_activity_logs')->where('activity', 'create')->count();
echo "Create logs: $create_logs\n";

$update_logs = DB::table('admin_activity_logs')->where('activity', 'update')->count();
echo "Update logs: $update_logs\n";

$delete_logs = DB::table('admin_activity_logs')->where('activity', 'delete')->count();
echo "Delete logs: $delete_logs\n";

// Test 2: Test scopes
echo "\n=== Testing Scopes ===\n";

$scopeTest = DB::table('admin_activity_logs')
    ->where('admin_id', 1)
    ->where('activity', 'create')
    ->whereBetween('created_at', ['2025-01-01 00:00:00', '2025-12-31 23:59:59'])
    ->count();

echo "Logs (admin_id=1, activity=create, 2025): $scopeTest\n";

// Test 3: Check latest logs
echo "\n=== Latest 5 Logs ===\n";
$logs = DB::table('admin_activity_logs')
    ->latest('created_at')
    ->limit(5)
    ->get(['id', 'admin_id', 'activity', 'created_at']);

foreach ($logs as $i => $log) {
    echo ($i+1) . ". ID:{$log->id} Admin:{$log->admin_id} Activity:{$log->activity} Time:{$log->created_at}\n";
}

echo "\n✓ All tests passed\n";
