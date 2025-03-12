<?php

namespace App\Jobs;

use App\Models\ContactNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMonthlyReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $penerimaNotif = ContactNotification::where('menerima_notif', 1)->get();

        foreach ($penerimaNotif as $contact) {
            // Kirim notifikasi (WhatsApp atau SMS)
            \Log::info("Mengirim laporan ke {$contact->nama} ({$contact->no_telepon})");
        }
    }
}
