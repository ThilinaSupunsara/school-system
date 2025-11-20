<?php

namespace App\Providers;

use App\Models\SchoolSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
            try {
            $settings = SchoolSetting::first();
            // 'schoolSettings' කියන නමින් හැම view එකකටම යවනවා
            View::share('schoolSettings', $settings);
        } catch (\Exception $e) {
            // මුලදී table එක නැති වුණාට ප්‍රශ්නයක් නෑ
        }
    }
}
