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
        Schema::create('nomination_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('points_reward')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('nominations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('nomination_categories')->onDelete('cascade');
            $table->foreignId('nominator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('nominee_id')->constrained('users')->onDelete('cascade');
            $table->text('reason')->nullable();
            $table->timestamps();

            // Employee can nominated other employee once per category
            $table->unique(['category_id', 'nominator_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nominations');
        Schema::dropIfExists('nomination_categories');
    }
};
