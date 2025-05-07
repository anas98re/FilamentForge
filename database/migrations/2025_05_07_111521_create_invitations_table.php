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
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_group_id')->constrained('invitation_groups')->onDelete('cascade'); // مفتاح خارجي
            $table->string('email');
            $table->string('token')->unique()->nullable(); // رمز فريد للدعوة
            $table->enum('status', ['pending', 'sent', 'opened', 'completed', 'expired', 'failed'])->default('pending'); // حالات الدعوة
            $table->string('language_code', 2); // 'ar', 'en'
            $table->timestamp('sent_at')->nullable(); // وقت الإرسال
            $table->timestamp('expires_at')->nullable(); // وقت انتهاء الصلاحية
            $table->timestamp('completed_at')->nullable(); // وقت الإكمال
            $table->timestamps();

            // يمكن إضافة فهرس للبريد الإلكتروني وحالة الدعوة لتسريع البحث
            $table->index(['email', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
