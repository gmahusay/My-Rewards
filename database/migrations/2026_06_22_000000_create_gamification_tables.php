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
        // 1. Wallets
        Schema::create('gamification_points_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('xp_total')->default(0);
            $table->integer('stardust_balance')->default(0);
            $table->timestamps();
        });

        // 2. Transactions
        Schema::create('gamification_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained('gamification_points_wallets')->cascadeOnDelete();
            $table->integer('points');
            $table->string('type'); // xp or stardust
            $table->string('description');
            $table->string('reference_id')->nullable();
            $table->timestamps();
        });

        // 3. Activity Log
        Schema::create('gamification_activities_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('action_name');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // 4. Levels (Global)
        Schema::create('gamification_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('xp_required');
            $table->string('icon_url')->nullable();
            $table->timestamps();
        });

        // 5. User Levels
        Schema::create('gamification_user_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('level_id')->constrained('gamification_levels')->cascadeOnDelete();
            $table->timestamp('reached_at')->useCurrent();
            $table->timestamps();
        });

        // 6. Badges (Global)
        Schema::create('gamification_badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon_url')->nullable();
            $table->string('trigger_event')->nullable();
            $table->timestamps();
        });

        // 7. User Badges
        Schema::create('gamification_user_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('badge_id')->constrained('gamification_badges')->cascadeOnDelete();
            $table->timestamp('awarded_at')->useCurrent();
            $table->timestamps();
        });

        // 8. Missions (Global)
        Schema::create('gamification_missions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('required_count');
            $table->string('event_type');
            $table->integer('reward_points');
            $table->timestamps();
        });

        // 9. User Missions
        Schema::create('gamification_user_missions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mission_id')->constrained('gamification_missions')->cascadeOnDelete();
            $table->integer('current_progress')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gamification_user_missions');
        Schema::dropIfExists('gamification_missions');
        Schema::dropIfExists('gamification_user_badges');
        Schema::dropIfExists('gamification_badges');
        Schema::dropIfExists('gamification_user_levels');
        Schema::dropIfExists('gamification_levels');
        Schema::dropIfExists('gamification_activities_log');
        Schema::dropIfExists('gamification_transactions');
        Schema::dropIfExists('gamification_points_wallets');
    }
};
