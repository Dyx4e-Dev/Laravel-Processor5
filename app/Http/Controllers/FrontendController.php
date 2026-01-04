<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\WebSetting;
use App\Models\Glossary;
use App\Models\Benchmark;
use App\Models\QuizResult; // Pastikan model ini sudah dibuat
use App\Models\Quiz;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
        $teams = Team::orderBy('id')->get();
        $webSetting = WebSetting::first();
        $glossary = Glossary::orderBy('id')->get();
        $benchmark = Benchmark::orderBy('id')->get();
        $quizzes = Quiz::inRandomOrder()->limit(10)->get();
        $userEmail = $request->cookie('quiz_user_email');
        $quiz_results = null;
        
        if ($userEmail) {
            $quiz_results = \App\Models\QuizResult::where('email', $userEmail)->latest()->first();
        }

        return view('frontend.layouts.app', compact(
            'teams', 
            'webSetting', 
            'glossary', 
            'benchmark',
            'quizzes',
            'quiz_results'
        ));
    }

    public function submitQuiz(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'who_explain' => 'required|integer|exists:teams,id',
            'score' => 'required|numeric|min:0|max:10', 
        ]);

        try {
            // 2. Logika Penentuan Hadiah (Reward)
            $score = intval($validated['score']);
            if ($score === 10) {
                $rewardStatus = 'kamu memahami materi';
            } else if ($score >= 7) {
                $rewardStatus = 'kamu cukup memahami materi';
            } else {
                $rewardStatus = 'Belajar lagi materi Single vs Multi Core ya!';
            }

            // 3. Simpan ke Tabel QuizResult
            $result = QuizResult::create([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'team_id' => $validated['who_explain'],
                'score' => $score,
                'reward_status' => $rewardStatus,
            ]);

            // 4. Respon JSON untuk Frontend
            return response()->json([
                'success' => true,
                'message' => 'Data quiz berhasil disimpan!',
                'data' => $result
            ])->cookie('quiz_user_email', $result->email, 1440);
        } catch (\Exception $e) {
            \Log::error('Quiz submission error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data quiz: ' . $e->getMessage()
            ], 500);
        }
    }
}