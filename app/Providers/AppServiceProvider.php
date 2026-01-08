<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Benchmark;
use App\Models\Quiz;
use App\Models\Glossary;
use App\Models\Team;
use App\Models\WebSetting;
use App\Models\BestCpu;
use App\Observers\BenchmarkObserver;
use App\Observers\QuizObserver;
use App\Observers\GlossaryObserver;
use App\Observers\TeamObserver;
use App\Observers\WebSettingObserver;
use App\Observers\BestCpuObserver;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Listeners\LogAdminLogin;
use App\Listeners\LogAdminLogout;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register observers untuk activity logging
        Benchmark::observe(BenchmarkObserver::class);
        Quiz::observe(QuizObserver::class);
        Glossary::observe(GlossaryObserver::class);
        Team::observe(TeamObserver::class);
        WebSetting::observe(WebSettingObserver::class);
        BestCpu::observe(BestCpuObserver::class);

        // Register event listeners untuk login/logout
        \Illuminate\Support\Facades\Event::listen(Login::class, LogAdminLogin::class);
        \Illuminate\Support\Facades\Event::listen(Logout::class, LogAdminLogout::class);
    }
}
