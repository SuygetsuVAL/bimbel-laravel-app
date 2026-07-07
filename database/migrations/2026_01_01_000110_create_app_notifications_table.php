<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Menggunakan nama tabel 'app_notifications' untuk tidak bentrok
        // dengan tabel 'notifications' bawaan Laravel (jika ada)
        Schema::create('app_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('message');
            $table->enum('target_role', ['all', 'admin', 'tutor', 'student', 'parent']);
            $table->unsignedBigInteger('target_id')->nullable();   // null = broadcast ke semua role
            $table->boolean('is_read')->default(false);
            $table->foreignId('sent_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_notifications');
    }
};
