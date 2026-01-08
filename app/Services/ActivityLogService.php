<?php

namespace App\Services;

use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    /**
     * Catat aktivitas admin ke database
     */
    public static function log(string $activity, ?string $description = null): void
    {
        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'activity' => $activity,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Catat aktivitas Create
     */
    public static function logCreate(string $model, ?array $data = null): void
    {
        $description = "Membuat " . $model;
        if ($data && is_array($data)) {
            $parts = [];
            foreach ($data as $k => $v) {
                if (is_array($v)) {
                    $v = json_encode($v);
                } elseif (is_bool($v)) {
                    $v = $v ? '1' : '0';
                }
                $parts[] = $k . ': ' . (string) $v;
            }
            if (count($parts)) {
                $description .= ' | ' . implode(', ', $parts);
            }
        }
        self::log('create', $description);
    }

    /**
     * Catat aktivitas Update
     */
    public static function logUpdate(string $model, $id, ?array $changes = null): void
    {
        $description = "Mengubah " . $model . " dengan ID: " . $id;
        if ($changes) {
            $description .= " | " . json_encode($changes);
        }
        self::log('update', $description);
    }

    /**
     * Catat aktivitas Delete
     */
    public static function logDelete(string $model, $id): void
    {
        // If observer passed full attributes as array
        if (is_array($id)) {
            $attrs = $id;
            $idVal = $attrs['id'] ?? 'N/A';
            $description = "Menghapus " . $model . " dengan ID: " . $idVal;
            $parts = [];
            foreach ($attrs as $k => $v) {
                if (is_array($v)) $v = json_encode($v);
                elseif (is_bool($v)) $v = $v ? '1' : '0';
                $parts[] = $k . ': ' . (string) $v;
            }
            if (count($parts)) {
                $description .= ' | ' . implode(', ', $parts);
            }
        } else {
            $description = "Menghapus " . $model . " dengan ID: " . $id;
        }

        self::log('delete', $description);
    }
}
