<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\WebSettingController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\GlossaryController;
use App\Http\Controllers\Admin\BenchmarkController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\QuizResultController;
use App\Http\Controllers\FrontendController;




/*
|--------------------------------------------------------------------------
| FRONTEND
|--------------------------------------------------------------------------
*/

// Halaman Utama
Route::get('/', [FrontendController::class, 'index']);

// Endpoint Simpan Quiz (Pastikan URL sesuai dengan fetch di JS)
Route::post('/', [FrontendController::class, 'submitQuiz']);
Route::post('/submit-quiz', [FrontendController::class, 'submitQuiz']);

/*
|--------------------------------------------------------------------------
| BACKEND - ADMIN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {

    // Login Routes
    Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    // Dashboard 
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Settings Routes
    Route::get('/settings', [WebSettingController::class, 'index'])->name('admin.settings');
    Route::post('/settings', [WebSettingController::class, 'update'])->name('admin.settings.update');

    // Teams Routes
    Route::get('/team', [TeamController::class, 'index'])->name('admin.team');
    Route::post('/team', [TeamController::class, 'store'])->name('admin.team.store');
    Route::put('team/{team}', [TeamController::class, 'update'])->name('admin.team.update');
    Route::delete('/team/{team}', [TeamController::class, 'destroy'])->name('admin.team.destroy');

    //Glossary Routes
    Route::get('/glossary', [GlossaryController::class, 'index'])->name('admin.glossary');

    Route::post('/glossary', [GlossaryController::class, 'store'])->name('glossary.store');
    Route::put('/glossary/{id}', [GlossaryController::class, 'update'])->name('glossary.update');
    Route::delete('/glossary/{id}', [GlossaryController::class, 'destroy'])->name('glossary.destroy');

    //Quiz Routes
    Route::get('/quiz', [QuizController::class, 'index'])->name('admin.quiz');
    Route::post('/quiz', [QuizController::class, 'store'])->name('admin.quiz.store');
    Route::put('/quiz/{id}', [QuizController::class, 'update'])->name('admin.quiz.update');
    Route::delete('/quiz/{id}', [QuizController::class, 'destroy'])->name('admin.quiz.destroy');

    Route::get('/quiz-results', [QuizResultController::class, 'index'])->name('admin.quiz_result');
    Route::delete('/quiz-results/{id}', [QuizResultController::class, 'destroy'])->name('admin.quiz_result.destroy');
    Route::post('/quiz-results/flush', [QuizResultController::class, 'flush'])->name('admin.quiz_result.flush');

    // Benchmark Routes
    Route::get('/benchmark', [BenchmarkController::class, 'index'])->name('admin.benchmark');
    Route::post('/benchmark/{id}', [BenchmarkController::class, 'storeOrUpdate'])->name('admin.benchmark.update');
    Route::post('/benchmark', [BenchmarkController::class, 'store'])->name('admin.benchmark.store');
    Route::delete('/benchmark/{id}', [BenchmarkController::class, 'destroy'])->name('admin.benchmark.destroy');


});
