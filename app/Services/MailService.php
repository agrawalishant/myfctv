<?php

namespace App\Services;

use App\Models\SmtpSetting;

class MailService
{
    public static function setSmtpConfig()
    {
        // Retrieve SMTP settings
        $smtpSettings = SmtpSetting::first();

        // Set configuration values dynamically
        config([
            'mail.mailers.smtp.host' => $smtpSettings->host,
            'mail.mailers.smtp.port' => $smtpSettings->port,
            'mail.mailers.smtp.encryption' => $smtpSettings->encryption,
            'mail.mailers.smtp.username' => $smtpSettings->username,
            'mail.mailers.smtp.password' => $smtpSettings->password,
        ]);

        config([
            'mail.from.address' => 'quantumroamer@gmail.com', // Replace with your desired default "from" address
            'mail.from.name' => config('app.name'),
        ]);
    }
}