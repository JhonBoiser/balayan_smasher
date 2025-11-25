<?php


// ============================================
// ARTISAN COMMAND TO CHECK SMS BALANCE
// php artisan make:command CheckSmsBalance
// app/Console/Commands/CheckSmsBalance.php
// ============================================
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SmsService;

class CheckSmsBalance extends Command
{
    protected $signature = 'sms:balance';
    protected $description = 'Check SMS account balance';

    public function handle(SmsService $smsService)
    {
        $this->info('Checking SMS balance...');

        $config = $smsService->testConfiguration();

        if (!$config['configured']) {
            $this->error('❌ ' . $config['message']);
            return 1;
        }

        $this->info('✅ SMS Service Status: Active');
        $this->line('');
        $this->table(
            ['Property', 'Value'],
            [
                ['Account ID', $config['account_id'] ?? 'N/A'],
                ['Account Name', $config['account_name'] ?? 'N/A'],
                ['Credits', $config['credits'] ?? 'N/A'],
            ]
        );

        return 0;
    }
}
