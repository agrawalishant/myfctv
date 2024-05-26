<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use App\Models\SmtpSetting;

class SmtpSettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        // Use view composer to share SMTP settings data with all views
        try {
            // Use view composer to share SMTP settings data with all views
            View::composer('*', function ($view) {
                $smtpSettings = SmtpSetting::first();

                // Debugging: Log the retrieved SMTP settings
                // \Log::debug('Retrieved SMTP Settings:', ['settings' => $smtpSettings]);

                $view->with('smtpSettings', $smtpSettings);
            });

            // Register the custom mail driver
            $this->app->singleton('smtp_credentials', function () {
                return SmtpSetting::first();
            });
        } catch (\Exception $e) {
            // Debugging: Log any exceptions that occur
            \Log::error('SmtpSettingsServiceProvider Exception:', ['message' => $e->getMessage()]);
        }
    }
}
