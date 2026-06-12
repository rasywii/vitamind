<?php

namespace App\Providers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mailer\Transport;

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
        // Transporte de correo por API de Brevo (HTTPS). Render bloquea el
        // puerto SMTP, asi que enviamos por API para que el correo si salga.
        Mail::extend('brevo', function () {
            return Transport::fromDsn('brevo+api://' . config('services.brevo.key') . '@default');
        });
    }
}
