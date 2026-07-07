<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('period', 20);   // Format: YYYY-MM
            $table->decimal('amount', 12, 2);
            $table->enum('method', ['bca', 'mandiri', 'gopay', 'ovo', 'tunai'])->nullable();
            $table->enum('status', ['belum_lunas', 'lunas', 'cicilan'])->default('belum_lunas');
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
