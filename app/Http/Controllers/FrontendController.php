<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\WebSetting;
use App\Models\Glossary;
use App\Models\Benchmark;
use App\Models\QuizResult; // Pastikan model ini sudah dibuat
use App\Models\Quiz;
use App\Models\Laptop;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
        $teams = Team::orderBy('id')->get();
        $webSetting = WebSetting::first();
        $glossary = Glossary::orderBy('id')->get();
        $benchmark = Benchmark::with('result', 'bestCpus')->orderBy('id')->get();
        $quizzes = Quiz::inRandomOrder()->limit(10)->get();
        $userEmail = $request->cookie('quiz_user_email');
        $quiz_results = null;
        
        if ($userEmail) {
            $quiz_results = QuizResult::where('email', $userEmail)->latest()->first();
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
    public function recommend(Request $request)
    {
        try {
            $budget = $request->input('budget'); // 'low', 'medium', 'high'
            $usage = $request->input('usage'); // array of usage types
            $appUsage = $request->input('app_usage'); // 'single-threaded' or 'multi-threaded'

            // Validate inputs
            if (!$budget || !$usage || !$appUsage) {
                return response()->json([
                    'error' => 'Missing required parameters: budget, usage, app_usage'
                ], 400);
            }

            if (!is_array($usage)) {
                return response()->json([
                    'error' => 'Usage must be an array'
                ], 400);
            }

            // Define price ranges based on budget
            $priceRanges = [
                'low' => [0, 15000000], // Under 15M
                'medium' => [15000000, 25000000], // 15M - 25M
                'high' => [25000000, 100000000] // Above 25M
            ];

            if (!isset($priceRanges[$budget])) {
                return response()->json([
                    'error' => 'Invalid budget value'
                ], 400);
            }

            [$minPrice, $maxPrice] = $priceRanges[$budget];

            // App usage values match database directly (no mapping needed)
            $dbAppUsages = [$appUsage];

            // Usage values match database directly (no mapping needed)
            $mappedUsage = $usage;

            // Query laptops with filters
            $query = Laptop::whereBetween('price', [$minPrice, $maxPrice])
                          ->whereIn('app_usage', $dbAppUsages);

            // Filter by usage - check if recommendation array contains any of the selected usage types
            // Handle both flat array format and nested format with 'penggunaan' key
            $query->where(function ($q) use ($mappedUsage) {
                foreach ($mappedUsage as $use) {
                    $q->orWhereJsonContains('recommendation', $use)
                      ->orWhereJsonContains('recommendation->penggunaan', $use);
                }
            });

            $recommendations = $query->get();

            return response()->json([
                'laptops' => $recommendations
            ]);
        } catch (\Exception $e) {
            \Log::error('Recommendation error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Internal server error'
            ], 500);
        }
    }
}