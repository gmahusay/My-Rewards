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
    Schema::table('referral_categories', function (Blueprint $table) {
        $table->string('referral_link')->default('')->change();
    });
}

public function down(): void
{
    Schema::table('referral_categories', function (Blueprint $table) {
        $table->dropColumn('referral_link');
    });
}
};
