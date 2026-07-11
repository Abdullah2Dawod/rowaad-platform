<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestMail extends Command
{
    protected $signature = 'mail:test {email? : Recipient email (defaults to admin@rowaad.org)}';
    protected $description = 'Send a test email to verify SMTP / Mailtrap configuration';

    public function handle(): int
    {
        $to = $this->argument('email') ?? 'admin@rowaad.org';
        $mailer = config('mail.default');

        $this->info("Sending test email using [{$mailer}] to: {$to}");

        try {
            Mail::raw(
                "هذه رسالة اختبار من منصة رواد بلا حدود.\n" .
                "إذا وصلتك هذه الرسالة فإن إعداد البريد يعمل بنجاح.\n\n" .
                "الوقت: " . now()->format('Y-m-d H:i:s') . "\n" .
                "المرسل: " . config('mail.from.address'),
                function ($message) use ($to) {
                    $message->to($to)
                        ->subject('✅ اختبار بريد — منصة رواد بلا حدود');
                }
            );

            $this->newLine();
            $this->info('✓ تم إرسال الرسالة بنجاح.');

            if ($mailer === 'smtp' && str_contains(config('mail.mailers.smtp.host', ''), 'mailtrap')) {
                $this->line('  افتح صندوق Mailtrap لرؤية الرسالة: https://mailtrap.io/inboxes');
            } elseif ($mailer === 'log') {
                $this->line('  الرسالة كُتبت في: storage/logs/laravel.log');
            }
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('✗ فشل الإرسال: ' . $e->getMessage());
            $this->newLine();
            $this->warn('تحقق من:');
            $this->line('  1) MAIL_USERNAME و MAIL_PASSWORD في .env');
            $this->line('  2) اتصالك بالإنترنت');
            $this->line('  3) بعد أي تعديل: php artisan config:clear');
            return self::FAILURE;
        }
    }
}
