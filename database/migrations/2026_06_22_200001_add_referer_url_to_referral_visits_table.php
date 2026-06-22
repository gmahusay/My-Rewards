<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('referral_visits', function (Blueprint $table) {
            $table->string('referer_url', 1000)->nullable()->after('user_agent');
        });
    }

    public function down(): void
    {
        Schema::table('referral_visits', function (Blueprint $table) {
            $table->dropColumn('referer_url');
        });
    }
};
