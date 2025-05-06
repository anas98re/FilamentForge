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
        Schema::create('raters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->onDelete('cascade');
            $table->foreignId('rater_group_id')->constrained('rater_groups')->onDelete('cascade');
            $table->string('email');
            $table->string('invitation_status')->default('pending');
            $table->timestamp('invitation_sent_at')->nullable();
            $table->timestamp('last_opened_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('num_reminders_sent')->default(0);
            $table->timestamp('last_reminder_sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raters');
    }
};
