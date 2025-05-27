<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // فقط نغيّر الدومين إذا كنا على local
        if ($this->app->environment('local')) {
            URL::forceRootUrl(config('app.url')); // تأكد أن APP_URL مضبوط في .env

            // إذا كنت تستخدم http وليس https
            URL::forceScheme('http');
        }

        // لا نستخدم createUrlUsing هنا لأن Laravel يتولى أمر التوقيع بشكل آمن
        // وإلا سيتم استدعاؤها بدون وجود مستخدم وتؤدي لخطأ
    }
}
