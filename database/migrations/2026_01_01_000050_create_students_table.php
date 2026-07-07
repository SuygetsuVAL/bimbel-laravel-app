<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nis', 20)->unique();
            $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->string('parent_name', 255)->nullable();
            $table->string('parent_phone', 20)->nullable();
            $table->string('parent_email', 255)->nullable();
            $table->text('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->date('join_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
