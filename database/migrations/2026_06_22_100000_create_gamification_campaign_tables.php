<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gamification_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('reward_points')->default(0);  // XP awarded on completion
            $table->timestamps();
        });

        Schema::create('gamification_campaign_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('gamification_campaigns')->cascadeOnDelete();
            // target_type: purchase, referral, nomination, claim
            $table->string('target_type');
            $table->string('label')->nullable(); // human-friendly label
            $table->integer('target_value'); // e.g. buy 3 products, refer 5 users
            $table->timestamps();
        });

        Schema::create('gamification_campaign_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('gamification_campaigns')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['campaign_id', 'user_id']);
        });

        Schema::create('gamification_campaign_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->constrained('gamification_campaign_participants')->cascadeOnDelete();
            $table->foreignId('target_id')->constrained('gamification_campaign_targets')->cascadeOnDelete();
            $table->integer('current_value')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gamification_campaign_progress');
        Schema::dropIfExists('gamification_campaign_participants');
        Schema::dropIfExists('gamification_campaign_targets');
        Schema::dropIfExists('gamification_campaigns');
    }
};
