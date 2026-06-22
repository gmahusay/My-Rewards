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
        Schema::table('nomination_categories', function (Blueprint $table) {
            $table->foreignId('winner_id')->nullable()->after('points_reward')->constrained('users')->onDelete('set null');
            $table->timestamp('awarded_at')->nullable()->after('winner_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nomination_categories', function (Blueprint $table) {
            $table->dropForeign(['winner_id']);
            $table->dropColumn(['winner_id', 'awarded_at']);
        });
    }
};
