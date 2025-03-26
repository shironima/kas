<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Income;
use App\Models\Expense;
use App\Models\RT;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class SendWhatsAppNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Ambil semua RT yang terdaftar
        $rts = RT::whereNotNull('head_contact')->get();

        foreach ($rts as $rt) {
            $rtId = $rt->id;
            $headContact = $rt->head_contact;
            $rtName = $rt->name;
            $currentMonthYear = Carbon::now()->translatedFormat('F Y');

            // Cek apakah ada transaksi income atau expense di bulan ini
            $hasIncome = Income::where('rts_id', $rtId)
                               ->whereMonth('transaction_date', $currentMonth)
                               ->whereYear('transaction_date', $currentYear)
                               ->exists();

            $hasExpense = Expense::where('rts_id', $rtId)
                                 ->whereMonth('transaction_date', $currentMonth)
                                 ->whereYear('transaction_date', $currentYear)
                                 ->exists();

            if ($hasIncome || $hasExpense) {
                $message = "✅ *$rtName telah melaporkan keuangan bulan $currentMonth $currentYear*, data keuangan telah tercatat dengan baik. Terima kasih telah melaporkan keuangan bulan ini.";
            } else {
                $message = "⚠️ *$rtName belum melaporkan keuangan RT bulan ini. Mohon segera lakukan pelaporan.*";
            }

            $this->sendWhatsAppMessage($headContact, $message);
        }
    }

    private function sendWhatsAppMessage($phone, $message)
    {
        $apiKey = env('FONNTE_API_KEY');

        $response = Http::withHeaders([
            'Authorization' => $apiKey,
            'Content-Type'  => 'application/x-www-form-urlencoded',
        ])->asForm()->post('https://api.fonnte.com/send', [
            'target'    => $phone,
            'message'   => $message,
            'countryCode' => '62', 
        ]);

        if ($response->failed()) {
            \Log::error("Gagal mengirim pesan ke $phone: " . $response->body());
        } else {
            \Log::info("✅ Pesan WA berhasil dikirim ke $phone");
        }
    }


}
