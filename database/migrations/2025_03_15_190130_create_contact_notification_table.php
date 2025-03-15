<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contact_notification', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_telepon');
            $table->boolean('menerima_notif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_notification');
    }
};
