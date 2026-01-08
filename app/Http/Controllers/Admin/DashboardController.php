<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\AdminActivityLog;
use App\Models\User;
use App\Models\QuizResult;
use App\Models\Team;
use App\Models\Quiz;
use App\Models\Glossary;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index(){
        Carbon::setLocale('id');

        // 1. Stats (Tetap sama)
        $stats = [
            'total_quiz'      => Quiz::count(),
            'total_peserta'   => QuizResult::distinct('team_id')->count('team_id'),
            'total_hasil'     => QuizResult::count(),
            'total_team'      => Team::count(),
            'total_glosarium' => Glossary::count(),
        ];

        // 2. Data Chart (7 Hari Terakhir)
        $chart_labels = [];
        $chart_data = [];
            
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            // Menampilkan format: "Senin, 01 Jan"
            $chart_labels[] = $date->translatedFormat('l, d M'); 
            
            // Menghitung partisipasi semua quiz pada tanggal tersebut
            $chart_data[] = QuizResult::whereDate('created_at', $date->toDateString())->count();
        }

        $recent_activities = AdminActivityLog::latest()->take(5)->get();
        $quiz_results = QuizResult::with('team')->orderByDesc('score')->take(10)->get();

        $chartData = $this->getChartData(Carbon::now()->subDays(6), Carbon::now());

        return view('backend.dashboard', array_merge(compact('stats', 'recent_activities', 'quiz_results'), $chartData));
    }

    public function filterChart(Request $request){
        // Mengambil 1 tanggal dari input
        $selectedDate = Carbon::parse($request->date);
        
        $labels = [];
        $values = [];
        
        // Menampilkan 7 hari ke belakang dari tanggal yang dipilih
        for ($i = 6; $i >= 0; $i--) {
            $date = $selectedDate->copy()->subDays($i);
            $labels[] = $date->translatedFormat('l, d M');
            $values[] = QuizResult::whereDate('created_at', $date->toDateString())->count();
        }

        return response()->json([
            'chart_labels' => $labels,
            'chart_data' => $values
        ]);
    }

    private function getChartData($start, $end){
        $labels = [];
        $values = [];
        
        // Iterasi dari tanggal mulai hingga tanggal selesai
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $labels[] = $date->translatedFormat('l, d M');
            $values[] = QuizResult::whereDate('created_at', $date->toDateString())->count();
        }

        return ['chart_labels' => $labels, 'chart_data' => $values];
    }
}
