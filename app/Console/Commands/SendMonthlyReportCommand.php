<?php

namespace App\Console\Commands;

use App\Jobs\SendWhatsAppNotification;
use Illuminate\Console\Command;

class SendMonthlyReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim laporan bulanan ke setiap ketua RT via WhatsApp';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        SendWhatsAppNotification::dispatchSync();;
        $this->info('Laporan bulanan berhasil dikirim.');
    }
}
