<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class AdminActivityLogController extends Controller
{
    /**
     * Tampilkan halaman log aktivitas dengan filter
     */
    public function index(Request $request)
    {
        $query = AdminActivityLog::with('admin');

        // Filter berdasarkan admin
        if ($request->filled('admin_id')) {
            $query->byAdmin($request->admin_id);
        }

        // Filter berdasarkan activity type
        if ($request->filled('activity')) {
            $query->byActivity($request->activity);
        }

        // Filter berdasarkan date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date . ' 00:00:00';
            $endDate = $request->end_date . ' 23:59:59';
            $query->byDateRange($startDate, $endDate);
        }

        // Urutkan terbaru di atas, pagination 50 per halaman
        $logs = $query->orderBy('created_at', 'desc')
                     ->paginate(50);

        // Get list admin untuk dropdown filter
        $admins = User::where('role', 'admin')->get();

        // Get unique activities
        $activities = AdminActivityLog::distinct()->pluck('activity')->toArray();

        return view('backend.activity_logs', compact('logs', 'admins', 'activities'));
    }

    /**
     * Clear semua log atau berdasarkan filter
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'clear_type' => 'required|in:all,admin,activity,date_range',
        ]);

        $query = AdminActivityLog::query();

        switch ($request->clear_type) {
            case 'admin':
                $request->validate(['admin_id' => 'required|exists:users,id']);
                $query->byAdmin($request->admin_id);
                break;

            case 'activity':
                $request->validate(['activity' => 'required|string']);
                $query->byActivity($request->activity);
                break;

            case 'date_range':
                $request->validate([
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
                ]);
                $startDate = $request->start_date . ' 00:00:00';
                $endDate = $request->end_date . ' 23:59:59';
                $query->byDateRange($startDate, $endDate);
                break;
        }

        $count = $query->count();
        $query->delete();

        return redirect()->route('admin.activity_logs')
                       ->with('success', "Log aktivitas dihapus ($count records)");
    }

    /**
     * View detail log (optional)
     */
    public function show(Request $request, $id)
    {
        $log = AdminActivityLog::with('admin')->findOrFail($id);

        if ($request->ajax() || $request->wantsJson()) {
            return view('backend.activity_logs._detail', compact('log'));
        }

        return view('backend.activity_logs.show', compact('log'));
    }
}
