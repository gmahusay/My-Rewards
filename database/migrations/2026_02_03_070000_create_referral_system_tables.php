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
        Schema::create('referral_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->string('referral_link');
            $table->integer('points_reward');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('referral_categories')->onDelete('cascade');
            $table->foreignId('referrer_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('referred_email');
            $table->text('notes')->nullable();
            $table->integer('rewarded_points')->nullable(); // Snapshot of points given
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');
        Schema::dropIfExists('referral_categories');
    }
};
