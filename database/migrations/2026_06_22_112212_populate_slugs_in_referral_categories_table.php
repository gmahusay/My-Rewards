<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, add the slug column if it doesn't exist
        if (!Schema::hasColumn('referral_categories', 'slug')) {
            Schema::table('referral_categories', function (Blueprint $table) {
                $table->string('slug')->nullable()->unique()->after('name');
            });
        }

        // Populate slug for existing records
        DB::table('referral_categories')->whereNull('slug')->get()->each(function ($category) {
            DB::table('referral_categories')
                ->where('id', $category->id)
                ->update(['slug' => Str::slug($category->name)]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('referral_categories', 'slug')) {
            Schema::table('referral_categories', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
};