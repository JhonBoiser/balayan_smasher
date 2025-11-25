<?php

// ============================================
// ARTISAN COMMAND FOR TESTING SMS
// php artisan make:command TestSms
// app/Console/Commands/TestSms.php
// ============================================
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SmsService;

class TestSms extends Command
{
    protected $signature = 'sms:test {phone} {message}';
    protected $description = 'Test SMS sending';

    public function handle(SmsService $smsService)
    {
        $phone = $this->argument('phone');
        $message = $this->argument('message');

        $this->info("Testing SMS configuration...");

        // Test configuration first
        $config = $smsService->testConfiguration();

        if (!$config['configured']) {
            $this->error('❌ SMS Configuration Error:');
            $this->error($config['message']);
            $this->line('');
            $this->line('Please add to your .env file:');
            $this->line('SEMAPHORE_API_KEY=your_api_key_here');
            $this->line('SEMAPHORE_SENDER_NAME="Smashers Hub"');
            return 1;
        }

        $this->info('✅ SMS service is configured');
        if (isset($config['credits'])) {
            $this->info("Credits available: " . $config['credits']);
        }
        $this->line('');

        $this->info("Sending SMS to {$phone}...");

        $result = $smsService->send($phone, $message);

        if ($result['success']) {
            $this->info('✅ SMS sent successfully!');
            $this->info('Message ID: ' . ($result['message_id'] ?? 'N/A'));
        } else {
            $this->error('❌ SMS failed: ' . $result['error']);
        }

        return $result['success'] ? 0 : 1;
    }
}
