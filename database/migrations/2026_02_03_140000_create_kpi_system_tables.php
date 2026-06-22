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
        Schema::create('kpi_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->integer('points_reward')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('kpis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('kpi_categories')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('description');
            $table->string('proof_image_path')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->integer('rewarded_points')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpis');
        Schema::dropIfExists('kpi_categories');
    }
};
